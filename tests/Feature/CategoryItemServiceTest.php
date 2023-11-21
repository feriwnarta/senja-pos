<?php

namespace Tests\Feature;

use App\Service\CategoryItemService;
use App\Service\Impl\CategoryItemServiceImpl;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertNotNull;

class CategoryItemServiceTest extends TestCase
{

    private CategoryItemService $categoryItemService;

    public function testGetItemCursor()
    {

        $data = $this->categoryItemService->getItemCursor();
        assertNotNull($data);
        assertIsArray($data);
        assertIsArray($data['data']);
        Log::info($data);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryItemService = app()->make(CategoryItemServiceImpl::class);
    }


}
