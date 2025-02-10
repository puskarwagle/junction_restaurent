<div class="special-menu-area pt-100 pb-100" wire:ignore.self>
    <div class="container">
        <div class="section-title">
            <span class="top-title">Special Menu</span>
            <h2>The Junction Popular Menu</h2>
        </div>

        <div class="special-menu-tabs">
        <div x-data="{ activeTab: '{{ (string) $menuItems->keys()->first() }}' }">
        <ul class="nav nav-tabs">
        @foreach($menuItems as $type => $items)
            <li class="nav-item" wire:key="tab-{{ $type }}">
                <button class="nav-link"
                    :class="{ 'active': activeTab === '{{ $type }}' }"
                    @click="activeTab = '{{ $type }}'">
                    {{ ucfirst($type) }}
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach($menuItems as $type => $items)
            <div class="tab-pane fade"
                :class="{ 'show active': activeTab === '{{ $type }}' }">
                <div class="single-special-menu-content">
                    <div class="row">
                        @foreach($items as $menuItem)
                            <div class="col-lg-6 col-md-6" wire:key="menu-item-{{ $menuItem->id }}">
                                <div class="special-card shop-area 
                                    @if(in_array($menuItem->id, $selectedItems)) selected @endif"
                                     wire:click="toggleItem({{ $menuItem->id }})">
                                    <div class="row align-items-center">
                                        <div class="col-lg-3 col-4 single-shop-cart">
                                            <div class="special-menu-img">
                                                <img src="{{ $menuItem->image_path }}" alt="images">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-6">
                                            <div class="special-menu-text">
                                                <h3>{{ $menuItem->id }}</h3>
                                                <h3>{{ $menuItem->name }}</h3>
                                                <p>{{ $menuItem->description }}</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-2">
                                            <div class="special-menu-number">
                                                <span class="oldPrice">${{ number_format($menuItem->price, 2) }}</span>
                                            </div>
                                            <div class="special-menu-number">
                                                <span>${{ number_format($menuItem->discount, 2) }}</span>
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