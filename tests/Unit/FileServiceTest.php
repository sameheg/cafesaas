<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Tenant;
use App\Support\FileService;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_file_and_thumbnail(): void
    {
        Storage::fake('local');
        $tenant = Tenant::factory()->create();
        app(TenantManager::class)->setTenant($tenant);
        $service = new FileService;
        $upload = UploadedFile::fake()->image('photo.jpg', 600, 600);

        $file = $service->store($upload, 'local');

        Storage::disk('local')->assertExists($file->path);
        Storage::disk('local')->assertExists($file->thumbnail_path);
        $this->assertDatabaseHas('files', [
            'id' => $file->id,
            'tenant_id' => $tenant->id,
        ]);
    }

    public function test_temporary_url_for_local_disk(): void
    {
        Storage::fake('local');
        $tenant = Tenant::factory()->create();
        app(TenantManager::class)->setTenant($tenant);
        $service = new FileService;
        $upload = UploadedFile::fake()->create('doc.txt', 10);
        $file = $service->store($upload, 'local');

        $url = $service->temporaryUrl($file, 5);

        $this->assertStringContainsString('signature=', $url);
    }

    public function test_attach_file_to_model(): void
    {
        Storage::fake('local');
        $tenant = Tenant::factory()->create();
        app(TenantManager::class)->setTenant($tenant);
        $service = new FileService;
        $upload = UploadedFile::fake()->create('doc.txt', 10);
        $file = $service->store($upload, 'local');

        $customer = Customer::factory()->create();
        $customer->files()->attach($file);

        $customer->refresh();
        $this->assertCount(1, $customer->files);
    }
}
