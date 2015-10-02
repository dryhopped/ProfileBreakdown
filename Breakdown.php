<?php

class Breakdown {
    
    protected $data;
    
    public function __construct($input)
    {
        $this->data = json_decode($input, true);
    }
    
    public function profile($filter)
    {
        $profiles = $this->filterProfiles($this->data['data'], $filter);
        $counts = array();
        $length = count($profiles);
        $return = array();
        
        foreach ($profiles as $profile) {
            if (empty($counts[$profile['profile'][$filter]])) {
                $counts[$profile['profile'][$filter]] = 1;
            } else {
                $counts[$profile['profile'][$filter]]++;
            }
        }
        
        foreach ($counts as $value => $count) {
            $return[] = array(
                "attribute" => $filter,
                "value" => $value,
                "percentage" => round(($count / $length) * 100, 2)
            );
        }
        
        return json_encode($return);
    }
    
    public function responses($filters)
    {
        $profiles = $this->data['data'];
        foreach ($filters as $filter) {
            $profiles = $this->filterProfiles($profiles, $filter['attribute'], $filter['value']);
        }
        
        $options = $this->getOptionIds();
        $count = 0;
        $results = $options;
        
        foreach ($profiles as $profile) {
            foreach ($profile['response'] as $response) {
                $options[$response]++;
                $count++;
            }
        }
        
        foreach ($options as $option => $value) {
            $results[$option] = round(($value / $count) * 100, 2);
        }
        
        return json_encode($results);
    }
    
    public function attribute($option)
    {
        $profiles = $this->filterProfilesByResponse($this->data['data'], $option);
        $attributes = array();
        $results = array();
        
        foreach ($profiles as $profile) {
            foreach ($profile['profile'] as $attribute => $value) {
                if (array_key_exists($attribute, $attributes)) {
                    if (array_key_exists($value, $attributes[$attribute]['values'])) {
                        $attributes[$attribute]['values'][$value]++;
                    } else {
                        $attributes[$attribute]['values'][$value] = 1;
                    }
                } else {
                    $attributes[$attribute] = array('values' => array($value => 1));
                }
            }
        }
        
        foreach ($attributes as $key => $attribute) {
            $attribute_count = array_sum($attribute['values']);
            $results[$key] = array();
            foreach ($attribute['values'] as $value => $count) {
                $results[$key][] = array(
                    "value" => $value,
                    "percentage" => round(($count / $attribute_count) * 100, 2)
                );
            }
        }
        
        return json_encode(array($results));
    }
    
    protected function filterProfiles($data, $attribute, $value = null)
    {
        $profiles = array();
        foreach ($data as $person) {
            if (array_key_exists($attribute, $person['profile'])) {
                if (is_null($value) || $person['profile'][$attribute] == $value) {
                    $profiles[] = $person;
                }
            }
        }
        
        return $profiles;
    }
    
    protected function filterProfilesByResponse($data, $option)
    {
        $results = array();
        
        foreach ($data as $person) {
            if (in_array($option, $person['response'])) {
                $results[] = $person;
            }
        }
        
        return $results;
    }
    
    protected function getOptionIds()
    {
        $options = array();
        foreach ($this->data['options'] as $option) {
            $options[$option['id']] = 0;
        }
        return $options;
    }

}