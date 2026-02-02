<?php

namespace Tests\Unit;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_has_fillable_attributes()
    {
        $fillable = ['name', 'email', 'subject', 'message', 'is_read'];
        $this->assertEquals($fillable, (new Contact())->getFillable());
    }

    public function test_contact_has_boolean_cast_for_is_read()
    {
        $contact = Contact::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test Message',
            'is_read' => 0
        ]);

        $this->assertIsBool($contact->is_read);
        $this->assertFalse($contact->is_read);

        $contact->update(['is_read' => 1]);
        $this->assertTrue($contact->is_read);
    }

    public function test_create_contact()
    {
        $contact = Contact::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test Message',
            'is_read' => false
        ]);

        $this->assertDatabaseHas('contacts', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test Message',
            'is_read' => false
        ]);

        $this->assertEquals('John Doe', $contact->name);
        $this->assertEquals('john@example.com', $contact->email);
        $this->assertEquals('Test Subject', $contact->subject);
        $this->assertEquals('Test Message', $contact->message);
        $this->assertFalse($contact->is_read);
    }

    public function test_update_contact()
    {
        $contact = Contact::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test Message',
            'is_read' => false
        ]);

        $updated = $contact->update([
            'name' => 'Jane Doe',
            'is_read' => true
        ]);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('contacts', [
            'name' => 'Jane Doe',
            'is_read' => true
        ]);
        $this->assertDatabaseMissing('contacts', [
            'name' => 'John Doe'
        ]);
    }

    public function test_delete_contact()
    {
        $contact = Contact::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test Message',
            'is_read' => false
        ]);

        $deleted = $contact->delete();

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('contacts', [
            'name' => 'John Doe'
        ]);
    }
}