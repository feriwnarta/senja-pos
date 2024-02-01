<?php

namespace App\Livewire\PointOfSalesKasir\Page;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Point Of Sales')]
#[Layout('components.layouts.pos')]
class MenuOrder extends Component
{
    public $menuCS = [
        [
            'id' => 1,
            'menu_name' => 'Bubur Hot Pot',
            'image_url' => 'img/cs-menu/bubur-hot-pot.png',
        ],
        [
            'id' => 2,
            'menu_name' => 'Bubur Mix Pot',
            'image_url' => 'img/cs-menu/bubur-mix-pot.png',
        ],
        [
            'id' => 3,
            'menu_name' => 'Bubur Special Mix Pot',
            'image_url' => 'img/cs-menu/bubur-special-mix-pot.png',
        ],
        [
            'id' => 4,
            'menu_name' => 'Bubuk Special Mix Porsi',
            'image_url' => 'img/cs-menu/bubur-special-mix-porsi.png',
        ],
        [
            'id' => 5,
            'menu_name' => 'Bubur Mix Satuan',
            'image_url' => 'img/cs-menu/bubur-mix-satuan.png',
        ],
        [
            'id' => 6,
            'menu_name' => 'Bubur Satuan Plus',
            'image_url' => 'img/cs-menu/bubur-satuan-plus.png',
        ],
        [
            'id' => 7,
            'menu_name' => 'Chamie Pork',
            'image_url' => 'img/cs-menu/chamie-pork.png',
        ],
        [
            'id' => 8,
            'menu_name' => 'Chabihun Seafood',
            'image_url' => 'img/cs-menu/chabihun-seafood.png',
        ],
        [
            'id' => 9,
            'menu_name' => 'Chakwetiau Chicken',
            'image_url' => 'img/cs-menu/chakwetiau-chicken.png',
        ],
        [
            'id' => 10,
            'menu_name' => 'Kwetiau Siram',
            'image_url' => 'img/cs-menu/kwetiau-siram.png',
        ],
        [
            'id' => 11,
            'menu_name' => 'Yammie Chasio',
            'image_url' => 'img/cs-menu/yammie-chasio.png',
        ],
        [
            'id' => 12,
            'menu_name' => 'Yammie Pork',
            'image_url' => 'img/cs-menu/yammie-pork.png',
        ],
        [
            'id' => 13,
            'menu_name' => 'Yammie Pacamke',
            'image_url' => 'img/cs-menu/yammie-pacamke.png',
        ],
        [
            'id' => 14,
            'menu_name' => 'Fried Rice Pork',
            'image_url' => 'img/cs-menu/friedrice-pork.png',
        ],
        [
            'id' => 15,
            'menu_name' => 'Chasio Madu',
            'image_url' => 'img/cs-menu/chasio-madu.png',
        ],
        [
            'id' => 16,
            'menu_name' => 'Pacamke',
            'image_url' => 'img/cs-menu/pacamke.png',
        ],
        [
            'id' => 17,
            'menu_name' => 'Samcan Goreng',
            'image_url' => 'img/cs-menu/samcan-goreng.png',
        ],
        [
            'id' => 18,
            'menu_name' => 'Singkong Goreng Cabe Garam',
            'image_url' => 'img/cs-menu/singkong-goreng-cg.png',
        ],
        [
            'id' => 19,
            'menu_name' => 'Tahu Goreng Cabe Garam',
            'image_url' => 'img/cs-menu/tahu-goreng-cg.png',
        ],
    ];

    public function render()
    {
        return view('livewire.point-of-sales-kasir.page.menu-order');
    }
}
