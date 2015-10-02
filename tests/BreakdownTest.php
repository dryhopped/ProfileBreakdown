<?php

require_once('Breakdown.php');

class BreakdownTest extends PHPUnit_Framework_TestCase {

    protected $breakdown;

    public function __construct()
    {
        $data = file_get_contents('input.json');
        $this->breakdown = new Breakdown($data);
    }
    
    public function testProfileBreakdown()
    {
        $expected = array(
            '[{"attribute":"age","value":26,"percentage":66.67},{"attribute":"age","value":30,"percentage":33.33}]',
            '[{"attribute":"gender","value":"Male","percentage":66.67},{"attribute":"gender","value":"Female","percentage":33.33}]'
        );
        $this->assertEquals($expected[0], $this->breakdown->profile('age'));
        $this->assertEquals($expected[1], $this->breakdown->profile('gender'));
    }
    
    public function testResponsesBreakdown()
    {
        $filters = array(
            '[{"attribute":"gender","value":"Male"}]',
            '[{"attribute":"gender","value":"Female"}]'
        );
        $expected = array(
            '{"option-1":100,"option-2":0,"option-3":0,"option-4":0,"option-5":0}',
            '{"option-1":50,"option-2":0,"option-3":0,"option-4":0,"option-5":50}'
        );
        
        $this->assertEquals($expected[0], $this->breakdown->responses(json_decode($filters[0], true)));
        $this->assertEquals($expected[1], $this->breakdown->responses(json_decode($filters[1], true)));
    }
    
    public function testResponsesBreakdownWithMultipleFilters()
    {
        $filters = array(
            '[{"attribute":"gender","value":"Male"},{"attribute":"income","value":"income-1"}]',
            '[{"attribute":"gender","value":"Male"},{"attribute":"state","value":"CA"}]',
        );
        $expected = array(
            '{"option-1":100,"option-2":0,"option-3":0,"option-4":0,"option-5":0}',
            '{"option-1":100,"option-2":0,"option-3":0,"option-4":0,"option-5":0}'
        );
        
        $this->assertEquals($expected[0], $this->breakdown->responses(json_decode($filters[0], true)));
        $this->assertEquals($expected[1], $this->breakdown->responses(json_decode($filters[1], true)));
    }

    public function testAttributeBreakdown()
    {
        $expected = array(
            '[{"age":[{"value":26,"percentage":66.67},{"value":30,"percentage":33.33}],"gender":[{"value":"Male","percentage":66.67},{"value":"Female","percentage":33.33}],"income":[{"value":"income-1","percentage":66.67},{"value":"income-4","percentage":33.33}],"state":[{"value":"CA","percentage":100}]}]',
            '[{"age":[{"value":30,"percentage":100}],"gender":[{"value":"Female","percentage":100}],"income":[{"value":"income-4","percentage":100}],"state":[{"value":"CA","percentage":100}]}]'
        );
        
        $this->assertEquals($expected[0], $this->breakdown->attribute('option-1'));
        $this->assertEquals($expected[1], $this->breakdown->attribute('option-5'));
    }
    
}