<?php

declare(strict_types=1);

use Ux2Dev\Speedy\Http\PrintResult;

it('exposes body, contentType and filename', function () {
    $r = new PrintResult('binary-bytes', 'application/pdf', 'voucher-1.pdf');

    expect($r->body)->toBe('binary-bytes');
    expect($r->contentType)->toBe('application/pdf');
    expect($r->filename)->toBe('voucher-1.pdf');
    expect($r->bytes())->toBe('binary-bytes');
});

it('detects PDF content', function () {
    $r = new PrintResult('%PDF-1.4...', 'application/pdf', 'a.pdf');

    expect($r->isPdf())->toBeTrue();
    expect($r->isZpl())->toBeFalse();
});

it('detects ZPL content from text/plain', function () {
    $r = new PrintResult('^XA^FO0,0...', 'text/plain', null);

    expect($r->isZpl())->toBeTrue();
    expect($r->isPdf())->toBeFalse();
});

it('detects ZPL content from application/zpl', function () {
    $r = new PrintResult('^XA^FO0,0...', 'application/zpl', null);

    expect($r->isZpl())->toBeTrue();
});

it('writes bytes to disk via saveTo', function () {
    $path = tempnam(sys_get_temp_dir(), 'speedy-test-');
    $r = new PrintResult('hello bytes', 'application/pdf', 'a.pdf');

    $written = $r->saveTo($path);

    expect($written)->toBe(11);
    expect(file_get_contents($path))->toBe('hello bytes');
    unlink($path);
});
