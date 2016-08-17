<?php

namespace Tests;

use \Phake;

trait TraitVerifyResolver
{
    protected function verifyResolverCalls($resolver, $a=1, $b=1, $c=1, $d=1)
    {
        Phake::verify($resolver, Phake::times($a))->setDefaults(Phake::anyParameters());
        Phake::verify($resolver, Phake::times($b))->setRequired(Phake::anyParameters());
        Phake::verify($resolver, Phake::times($c))->setAllowedTypes(Phake::anyParameters());
        Phake::verify($resolver, Phake::times($d))->resolve(Phake::anyParameters());
    }
}
