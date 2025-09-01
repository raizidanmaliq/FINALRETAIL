<nav class="navbar navbar-marketing navbar-expand-lg shadow-sm px-lg-0 border-bottom border-white py-3">
	<div class="container align-items-center">
		<a class="navbar-brand" href="#">
			<span class="navbar-title">Binco Talent</span>
		</a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars text-white"></i>
        </button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto my-lg-0">
            <li class="nav-item">
                <a href="{{ route('front.directories.show', $talent->id) }}" class="nav-link">
                    <i class="fas fa-arrow-left mr-2"></i>
                    BACK
                </a>
            </li>
          </ul>
		</div>
	</div>
</nav>
