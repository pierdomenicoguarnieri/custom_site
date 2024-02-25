<?php
class stdModel {
    public function getProperty($name){
        if(isset($this->{$name})){
            return $this->{$name};
        }
    }
}