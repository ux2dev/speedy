<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Tests\Laravel;

it('registers the speedy config tag and publishes the file', function () {
    $target = config_path('speedy.php');

    if (file_exists($target)) {
        unlink($target);
    }

    $this->artisan('vendor:publish', ['--tag' => 'speedy-config'])->assertExitCode(0);

    expect(file_exists($target))->toBeTrue();

    unlink($target);
});
