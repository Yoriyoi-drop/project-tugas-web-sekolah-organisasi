<?php

namespace Tests\Unit;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingModelTest extends TestCase
{
    use RefreshDatabase;

    private function createSetting($overrides = [])
    {
        $setting = new Setting();
        $setting->key = $overrides['key'] ?? 'test_key';
        $setting->value = $overrides['value'] ?? 'test_value';
        $setting->save();
        
        return $setting;
    }

    public function test_setting_has_fillable_attributes()
    {
        $data = [
            'key' => 'site_name',
            'value' => 'My Website'
        ];

        $setting = Setting::create($data);

        $this->assertEquals($data['key'], $setting->key);
        $this->assertEquals($data['value'], $setting->value);
    }

    public function test_setting_can_be_created()
    {
        $setting = $this->createSetting();

        $this->assertInstanceOf(Setting::class, $setting);
        $this->assertDatabaseHas('settings', ['id' => $setting->id]);
    }

    public function test_setting_can_be_found()
    {
        $setting = $this->createSetting();
        
        $found = Setting::find($setting->id);
        
        $this->assertInstanceOf(Setting::class, $found);
        $this->assertEquals($setting->id, $found->id);
    }

    public function test_setting_can_be_updated()
    {
        $setting = $this->createSetting(['value' => 'original_value']);
        
        $setting->value = 'updated_value';
        $setting->save();
        
        $this->assertEquals('updated_value', $setting->fresh()->value);
    }

    public function test_setting_can_be_deleted()
    {
        $setting = $this->createSetting();
        
        $setting->delete();
        
        $this->assertDatabaseMissing('settings', ['id' => $setting->id]);
    }

    public function test_setting_query_scopes()
    {
        $this->createSetting(['key' => 'site_name', 'value' => 'My Site']);
        $this->createSetting(['key' => 'site_description', 'value' => 'Site Description']);
        $this->createSetting(['key' => 'admin_email', 'value' => 'admin@example.com']);

        $siteSettings = Setting::where('key', 'like', '%site%')->get();

        $this->assertCount(2, $siteSettings);
        $this->assertEquals('site_name', $siteSettings->first()->key);
    }

    public function test_setting_mass_assignment()
    {
        $data = [
            'key' => 'test_setting',
            'value' => 'test_value'
        ];
        
        $setting = new Setting($data);
        
        $this->assertEquals('test_setting', $setting->key);
        $this->assertEquals('test_value', $setting->value);
    }

    public function test_setting_unique_key_constraint()
    {
        $this->createSetting(['key' => 'unique_key']);
        
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        $this->createSetting(['key' => 'unique_key']);
    }

    public function test_static_get_method_with_existing_key()
    {
        $this->createSetting(['key' => 'site_name', 'value' => 'My Website']);

        $value = Setting::get('site_name');

        $this->assertEquals('My Website', $value);
    }

    public function test_static_get_method_with_non_existing_key()
    {
        $value = Setting::get('non_existing_key', 'default_value');

        $this->assertEquals('default_value', $value);
    }

    public function test_static_get_method_with_default_null()
    {
        $value = Setting::get('non_existing_key');

        $this->assertNull($value);
    }

    public function test_static_set_method_creates_new_setting()
    {
        $result = Setting::set('new_key', 'new_value');

        $this->assertInstanceOf(Setting::class, $result);
        $this->assertEquals('new_key', $result->key);
        $this->assertEquals('new_value', $result->value);
        $this->assertDatabaseHas('settings', ['key' => 'new_key', 'value' => 'new_value']);
    }

    public function test_static_set_method_updates_existing_setting()
    {
        $this->createSetting(['key' => 'existing_key', 'value' => 'old_value']);

        $result = Setting::set('existing_key', 'new_value');

        $this->assertInstanceOf(Setting::class, $result);
        $this->assertEquals('existing_key', $result->key);
        $this->assertEquals('new_value', $result->value);
        $this->assertDatabaseHas('settings', ['key' => 'existing_key', 'value' => 'new_value']);
    }

    public function test_static_get_method_caching()
    {
        $this->createSetting(['key' => 'cached_key', 'value' => 'cached_value']);

        // First call should hit database
        $value1 = Setting::get('cached_key');
        $this->assertEquals('cached_value', $value1);

        // Second call should use cache
        $value2 = Setting::get('cached_key');
        $this->assertEquals('cached_value', $value2);
    }

    public function test_static_set_method_clears_cache()
    {
        $this->createSetting(['key' => 'cache_test', 'value' => 'original_value']);

        // Get value to populate cache
        $originalValue = Setting::get('cache_test');
        $this->assertEquals('original_value', $originalValue);

        // Update value
        Setting::set('cache_test', 'updated_value');

        // Get updated value
        $updatedValue = Setting::get('cache_test');
        $this->assertEquals('updated_value', $updatedValue);
    }

    public function test_setting_with_different_value_types()
    {
        // String value
        $stringSetting = $this->createSetting(['key' => 'string_setting', 'value' => 'string_value']);
        $this->assertEquals('string_value', Setting::get('string_setting'));

        // Numeric value
        $numericSetting = $this->createSetting(['key' => 'numeric_setting', 'value' => '123']);
        $this->assertEquals('123', Setting::get('numeric_setting'));

        // Boolean value
        $booleanSetting = $this->createSetting(['key' => 'boolean_setting', 'value' => 'true']);
        $this->assertEquals('true', Setting::get('boolean_setting'));

        // Array value (as JSON)
        $arraySetting = $this->createSetting(['key' => 'array_setting', 'value' => '["item1", "item2"]']);
        $this->assertEquals('["item1", "item2"]', Setting::get('array_setting'));
    }

    public function test_setting_search_by_key()
    {
        $this->createSetting(['key' => 'app_name', 'value' => 'My App']);
        $this->createSetting(['key' => 'app_version', 'value' => '1.0.0']);
        $this->createSetting(['key' => 'app_description', 'value' => 'App Description']);

        $appSettings = Setting::where('key', 'like', '%app%')->get();

        $this->assertCount(3, $appSettings);
        $this->assertEquals('app_name', $appSettings->first()->key);
    }

    public function test_setting_search_by_value()
    {
        $this->createSetting(['key' => 'site_title', 'value' => 'Welcome to My Site']);
        $this->createSetting(['key' => 'admin_name', 'value' => 'Administrator']);
        $this->createSetting(['key' => 'contact_email', 'value' => 'contact@example.com']);

        $welcomeSettings = Setting::where('value', 'like', '%Welcome%')->get();

        $this->assertCount(1, $welcomeSettings);
        $this->assertEquals('site_title', $welcomeSettings->first()->key);
    }

    public function test_setting_bulk_operations()
    {
        $settings = [
            'setting1' => 'value1',
            'setting2' => 'value2',
            'setting3' => 'value3'
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        foreach ($settings as $key => $value) {
            $this->assertEquals($value, Setting::get($key));
        }
    }

    public function test_setting_update_or_create_logic()
    {
        // Test updateOrCreate with new key
        $result1 = Setting::updateOrCreate(['key' => 'test_key'], ['value' => 'initial_value']);
        $this->assertEquals('initial_value', $result1->value);

        // Test updateOrCreate with existing key
        $result2 = Setting::updateOrCreate(['key' => 'test_key'], ['value' => 'updated_value']);
        $this->assertEquals('updated_value', $result2->value);

        // Only one record should exist
        $this->assertEquals(1, Setting::where('key', 'test_key')->count());
    }

    public function test_setting_with_special_characters_in_key()
    {
        $setting = $this->createSetting(['key' => 'special.key-with.dots', 'value' => 'special_value']);

        $this->assertEquals('special_value', Setting::get('special.key-with.dots'));
    }

    public function test_setting_with_long_values()
    {
        $longValue = str_repeat('This is a very long value. ', 100);
        $setting = $this->createSetting(['key' => 'long_value', 'value' => $longValue]);

        $this->assertEquals($longValue, Setting::get('long_value'));
    }

    public function test_setting_case_sensitivity()
    {
        $this->createSetting(['key' => 'CaseSensitiveKey', 'value' => 'value1']);
        
        $this->assertEquals('value1', Setting::get('CaseSensitiveKey'));
        $this->assertNull(Setting::get('casesensitivekey'));
    }
}
