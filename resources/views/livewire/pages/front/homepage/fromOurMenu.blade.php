<div>
    <div class="special-menu-area pt-100 pb-100">
        <div class="container">
            <div class="section-title">
                <span class="top-title">Special Menu</span>
                <h2>The Junction Popular Menu</h2>
            </div>
            
            <div class="special-menu-tabs">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @foreach ($menuItems as $type => $items)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($loop->first) active @endif" 
                                id="{{ $type }}-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#{{ $type }}-tab-pane" 
                                type="button" 
                                role="tab" 
                                aria-controls="{{ $type }}-tab-pane" 
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ ucfirst($type) }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content" id="myTabContent">
                    @foreach ($menuItems as $type => $items)
                        <div class="tab-pane fade @if($loop->first) show active @endif" 
                            id="{{ $type }}-tab-pane" 
                            role="tabpanel" 
                            aria-labelledby="{{ $type }}-tab" 
                            tabindex="0">
                            <div class="single-special-menu-content">
                                <div class="row">
                                    @foreach ($items as $menuItem)
                                        <div class="col-lg-6 col-md-6">
                                            <div class="special-card shop-area">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-3 col-4 single-shop-cart">
                                                        <div class="special-menu-img">
                                                            <a href="menu-details.html">
                                                                <img src="{{ $menuItem->image_path }}" alt="images">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-6">
                                                        <div class="special-menu-text">
                                                            <a href="menu-details.html">
                                                                <h3>{{ $menuItem->name }}</h3>
                                                            </a>
                                                            <p>{{ $menuItem->description }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-2">
                                                        <div class="special-menu-number">
                                                            <span>${{ number_format($menuItem->price, 2) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>