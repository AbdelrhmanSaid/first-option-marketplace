<?php

test('phone rule passes with a valid phone number', function () {
    $rule = new App\Rules\Phone;

    $this->assertTrue($rule->passes('phone', '+201234567890'));
    $this->assertTrue($rule->passes('phone', '+14155552671'));
    $this->assertTrue($rule->passes('phone', '+447400123456'));
});

test('phone rule fails with an invalid phone number', function () {
    $rule = new App\Rules\Phone;

    $this->assertFalse($rule->passes('phone', '45134'));
    $this->assertFalse($rule->passes('phone', 'lorum ipsum'));
    $this->assertFalse($rule->passes('phone', '+201453'));
});
