<?php

namespace Uvinum\Joiner\Serializer;

use Uvinum\Joiner\ChildObject;
use Uvinum\Joiner\FakeObject;
use Uvinum\Joiner\FakeObjectWithArrayProperty;
use Uvinum\Joiner\Name;
use Uvinum\Joiner\NameWithToString;
use Uvinum\Joiner\Strategy\ToStringStrategy;

class ArraySerializerTest extends \PHPUnit_Framework_TestCase
{
    private $input;

    private $output;

    protected function setUp()
    {
        $this->input  = null;
        $this->output = null;
    }

    /** @test */
    public function shouldRetrieveAnArray()
    {
        $this->givenAnArrayWithPlainData();
        $this->whenSerializingTheInputData();
        $this->thenShouldAssertAnArray();
    }

    /** @test */
    public function shouldRetrieveAnObjectAsAnArray()
    {
        $this->givenAnObjectWith("Marcos");
        $this->whenSerializingTheInputData();
        $this->thenShouldAssertASerializedObject();
    }

    /** @test */
    public function shouldRetrieveAnObjectWithArrayPropertiesAsAnArray()
    {
        $this->givenAnObjectWithArrayProperties();
        $this->whenSerializingTheInputData();
        $this->thenShouldAssertASerializedObjectWithArray();
    }

    /** @test */
    public function shouldRetrieveAnArrayOfObjectAndObjectWithArrayPropertiesAsASerializedArray()
    {
        $this->givenAnArrayOfObjectAndAnObjectWithArrayProperties();
        $this->whenSerializingTheInputData();
        $this->thenShouldAssertAnArrayOfSerializedObjectAndSerializedObjectWithArray();
    }

    /** @test */
    public function shouldRetrieveASerializedArrayFromObjectWithObjectProperty()
    {
        $this->givenAnObjectWith(new Name("Marcos"));
        $this->whenSerializingTheInputData();
        $this->thenShouldAssertAnArrayOfSerializedObjectWithObjectProperty();
    }

    /** @test */
    public function shouldRetrieveASerializedArrayFromObjectWithObjectPropertyWithTooStringSet()
    {
        $this->givenAnObjectWith(new NameWithToString("Marcos"));
        $this->whenSerializingTheInputData();
        $this->thenShouldAssertAnArrayOfSerializedObjectWithObjectPropertyWithToStringSet();
    }

    /** @test */
    public function shouldRetrieveAllSerializePropertiesFromBothParentAndChild()
    {
        $this->givenAChildObject();
        $this->whenSerializingTheInputData();
        $this->thenShouldAssertASerializedObjectWithBothChildAndParentProperties();
    }

    private function givenAnArrayWithPlainData()
    {
        $this->input = [1, 2, 3];
    }

    private function givenAnObjectWith($name)
    {
        $this->input = new FakeObject($name);
    }

    private function givenAnObjectWithArrayProperties()
    {
        $this->input = new FakeObjectWithArrayProperty("Marcos", ["Spaguetti", "Macaroni", "Pizza"]);
    }

    private function givenAnArrayOfObjectAndAnObjectWithArrayProperties()
    {
        $this->input = [new FakeObject("Marcos"), new FakeObjectWithArrayProperty("Dave", ["Pizza", "Sushi", "Sandwich"])];
    }

    private function givenAChildObject()
    {
        $this->input = new ChildObject('12345', 'Marcos');
    }

    private function whenSerializingTheInputData()
    {
        $serializer   = new ArraySerializer(new ToStringStrategy());
        $this->output = $serializer->serialize($this->input);
    }

    private function thenShouldAssertAnArray()
    {
        $this->assertInternalType('array', $this->output);
    }

    private function thenShouldAssertASerializedObject()
    {
        $this->assertEquals(['name' => 'Marcos'], $this->output);
    }

    private function thenShouldAssertASerializedObjectWithArray()
    {
        $this->assertEquals([
            'name' => 'Marcos',
            'foods' => ["Spaguetti", "Macaroni", "Pizza"]
        ], $this->output);
    }

    private function thenShouldAssertAnArrayOfSerializedObjectAndSerializedObjectWithArray()
    {
        $this->assertEquals([
            0 => ['name' => 'Marcos'],
            1 => ['name' => 'Dave', 'foods' => ['Pizza', 'Sushi', 'Sandwich']]
        ], $this->output);
    }

    private function thenShouldAssertAnArrayOfSerializedObjectWithObjectProperty()
    {
        $this->assertEquals(["name" => ["name" => "Marcos"]], $this->output);
    }

    private function thenShouldAssertAnArrayOfSerializedObjectWithObjectPropertyWithToStringSet()
    {
        $this->assertEquals(["name" => "Marcos"], $this->output);
    }

    private function thenShouldAssertASerializedObjectWithBothChildAndParentProperties()
    {
        $this->assertEquals(['id' => '12345', 'name' => 'Marcos'], $this->output);
    }
}
