<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Cms\ProductCategory;
use App\Models\Inventory\Product;
use App\Models\Order;
use App\Models\OrderItem;

class ChatbotController extends Controller
{
    /**
     * Menampilkan halaman chatbot dan menginisialisasi sesi.
     */
    public function show()
    {
        Session::put('chatbot_state', 'initial');
        Session::forget('chatbot_data');

        $greeting = "Halo assalamualaikum, Salam Ahlinya Retail 🙏😊 Tempat terbaik\n\nSalam kenal saya customer service Ahlinya Retail, dengan kaka siapa kami berbicara?";

        return response()->json([
            'reply' => $greeting
        ]);
    }

    /**
     * Menangani input dari pengguna dan melanjutkan alur percakapan.
     */
    public function handle(Request $request)
    {
        $state = Session::get('chatbot_state', 'initial');
        $data = Session::get('chatbot_data', []);
        $reply = '';
        $options = [];

        if ($request->input('input') === 'new_chat') {
            Session::put('chatbot_state', 'initial');
            Session::forget('chatbot_data');
            $reply = "Halo assalamualaikum, Salam Ahlinya Retail 🙏😊 Tempat terbaik\n\nSalam kenal saya customer service Ahlinya Retail, dengan kaka siapa kami berbicara?";
            return response()->json(['reply' => $reply, 'options' => []]);
        }

        switch ($state) {
            case 'initial':
                $data['receiver_name'] = $request->input('input');
                $reply = "Baik, Kak {$data['receiver_name']}, ingin pesan apa nih? Silakan pilih kategori:";
                $options = ProductCategory::all(['id', 'name']);
                Session::put('chatbot_state', 'select_category');
                Session::put('chatbot_data', $data);
                break;

            case 'select_category':
                $data['category_id'] = $request->input('input');
                // PERBAIKAN: Menambahkan filter untuk is_displayed = true
                $products = Product::where('category_id', $data['category_id'])
                                   ->where('is_displayed', true)
                                   ->get(['id', 'name', 'selling_price']);

                if ($products->isEmpty()) {
                    $reply = "Maaf, tidak ada produk pada kategori tersebut. Silakan pilih kategori lain:";
                    $options = ProductCategory::all(['id', 'name']);
                } else {
                    $reply = "Pilih produk dari kategori yang Anda pilih:";
                    $options = $products;
                    Session::put('chatbot_state', 'select_product');
                }
                Session::put('chatbot_data', $data);
                break;

            case 'select_product':
                $data['product_id'] = $request->input('input');
                $product = Product::with('variants')->find($data['product_id']);

                if (!$product) {
                    $reply = "Produk tidak ditemukan. Silakan pilih ulang:";
                    $options = Product::where('category_id', $data['category_id'])->get(['id', 'name']);
                } else {
                    $data['product_name'] = $product->name;
                    $data['price'] = $product->selling_price ?? 0;

                    if ($product->variants->isNotEmpty()) {
                        $uniqueColors = $product->variants->pluck('color')->unique()->filter();

                        if ($uniqueColors->isNotEmpty()) {
                            $reply = "Pilih warna untuk produk {$data['product_name']}:";
                            $options = $uniqueColors->map(function($color) {
                                return ['id' => $color, 'name' => $color];
                            })->values()->toArray();
                            Session::put('chatbot_state', 'select_color');
                        } else {
                            $data['color'] = 'Tidak Ada';
                            $reply = "Pilih ukuran untuk produk {$data['product_name']}:";
                            $uniqueSizes = $product->variants->pluck('size')->unique()->filter();
                            $options = $uniqueSizes->map(function($size) {
                                return ['id' => $size, 'name' => $size];
                            })->values()->toArray();
                            Session::put('chatbot_state', 'select_size');
                        }
                    } else {
                        $data['color'] = 'Tidak Ada';
                        $data['size'] = 'Tidak Ada';
                        $reply = "Kak {$data['receiver_name']}, Anda memilih produk: {$data['product_name']} dengan harga Rp "
                            . number_format($data['price'], 0, ',', '.') . ". Berapa jumlah (pcs) yang ingin dipesan?";
                        Session::put('chatbot_state', 'input_quantity');
                    }
                }
                Session::put('chatbot_data', $data);
                break;

            case 'select_color':
                $data['color'] = $request->input('input');
                $product = Product::with('variants')->find($data['product_id']);

                $availableSizes = $product->variants
                    ->where('color', $data['color'])
                    ->pluck('size')
                    ->unique()
                    ->filter();

                if ($availableSizes->isNotEmpty()) {
                    $reply = "Anda pilih warna: {$data['color']}.\nSekarang pilih ukuran:";
                    $options = $availableSizes->map(function($size) {
                        return ['id' => $size, 'name' => $size];
                    })->values()->toArray();
                    Session::put('chatbot_state', 'select_size');
                } else {
                    $data['size'] = 'Tidak Ada';
                    $reply = "Kak {$data['receiver_name']}, Anda memilih produk: {$data['product_name']} (Warna: {$data['color']}) dengan harga Rp "
                        . number_format($data['price'], 0, ',', '.') . ". Berapa jumlah (pcs) yang ingin dipesan?";
                    Session::put('chatbot_state', 'input_quantity');
                }
                Session::put('chatbot_data', $data);
                break;

            case 'select_size':
                $data['size'] = $request->input('input');
                $reply = "Ukuran {$data['size']} dipilih.\n\nBerapa jumlah (pcs) yang ingin dipesan?";
                Session::put('chatbot_state', 'input_quantity');
                Session::put('chatbot_data', $data);
                break;

            case 'input_quantity':
                $data['quantity'] = (int) $request->input('input');
                $data['subtotal'] = $data['price'] * $data['quantity'];

                $reply = "Oke, {$data['quantity']} pcs (Subtotal: Rp "
                    . number_format($data['subtotal'], 0, ',', '.')
                    . ").\n\nMohon masukkan nomor handphone Anda:";
                Session::put('chatbot_state', 'input_phone');
                Session::put('chatbot_data', $data);
                break;

            case 'input_phone':
                $data['receiver_phone'] = $request->input('input');
                $reply = "Nomor handphone: {$data['receiver_phone']}\n\nMohon masukkan alamat email Anda:";
                Session::put('chatbot_state', 'input_email');
                Session::put('chatbot_data', $data);
                break;

            case 'input_email':
                $data['receiver_email'] = $request->input('input');
                $reply = "Mohon masukkan data alamat lengkap Anda:";
                Session::put('chatbot_state', 'input_address');
                Session::put('chatbot_data', $data);
                break;

            case 'input_address':
                $data['receiver_address'] = $request->input('input');
                $orderCode = 'ORD-' . time() . '-' . Str::upper(Str::random(5));

                $totalPrice = $data['subtotal'] ?? 0;

                $order = Order::create([
                    'customer_id'      => null,
                    'order_code'       => $orderCode,
                    'total_price'      => $totalPrice,
                    'receiver_name'    => $data['receiver_name'],
                    'receiver_phone'   => $data['receiver_phone'],
                    'receiver_email'   => $data['receiver_email'],
                    'receiver_address' => $data['receiver_address'],
                    'order_status'     => 'pending',
                ]);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $data['product_id'],
                    'quantity'   => $data['quantity'],
                    'price'      => $data['price'],
                    'color'      => $data['color'],
                    'size'       => $data['size'],
                    'subtotal'   => $data['subtotal'],
                ]);

                $adminPhoneNumber = '6285323227747';
                $whatsappMessage = "Halo, saya *{$data['receiver_name']}* (*{$data['receiver_phone']}*).\n\n";
                $whatsappMessage .= "Saya telah melakukan pemesanan di website dengan detail:\n\n";
                $whatsappMessage .= "*Kode Pesanan:* {$orderCode}\n";
                $whatsappMessage .= "*Total Pembayaran:* Rp " . number_format($totalPrice, 0, ',', '.') . "\n\n";
                $whatsappMessage .= "*Detail Produk:*\n";
                $whatsappMessage .= "- {$data['product_name']} ({$data['color']} / {$data['size']}) ({$data['quantity']} pcs) - Rp "
                    . number_format($data['price'], 0, ',', '.') . "\n\n";
                $whatsappMessage .= "*Alamat Pengiriman:*\n";
                $whatsappMessage .= "{$data['receiver_address']}\n\n";
                $whatsappMessage .= "Saya sudah melakukan pemesanan menunggu konfirmasi. Terima kasih.";

                $encodedMessage = urlencode($whatsappMessage);
                $whatsappUrl = "https://wa.me/{$adminPhoneNumber}?text={$encodedMessage}";

                $reply = "Terima kasih, Kak {$data['receiver_name']}! Pesanan Anda telah dibuat dengan kode: {$orderCode}.\n\nSilakan klik tombol di bawah untuk melanjutkan pesanan Anda ke WhatsApp admin kami.";
                $options = [
                    [
                        'id' => 'whatsapp_link',
                        'name' => 'Lanjut ke WhatsApp',
                        'url' => $whatsappUrl
                    ]
                ];

                Session::forget('chatbot_state');
                Session::forget('chatbot_data');
                break;

            default:
                $reply = "Halo, ada yang bisa saya bantu?";
                Session::put('chatbot_state', 'initial');
                Session::forget('chatbot_data');
                break;
        }

        return response()->json([
            'reply' => $reply,
            'options' => $options
        ]);
    }
}
