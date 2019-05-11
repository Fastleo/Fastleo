<?php

namespace Fastleo\Fastleo\Test;

use Tests\TestCase;

class UrlTest extends TestCase
{
    /** @test */
    public function testMain()
    {
        $response = $this->get('/fastleo');
        $response->assertStatus(200);
    }

    /** @test */
    public function testInfo()
    {
        $response = $this->get('/fastleo/info');
        $response->assertStatus(302);
    }

    /** @test */
    public function testAuth()
    {
        $response = $this->withSession(['admin' => 1])->get('/fastleo/info');
        $response->assertStatus(200);
    }

    /** @test */
    public function testFileManager()
    {
        $response = $this->get('/fastleo/filemanager');
        $response->assertStatus(302);
    }

    /** @test */
    public function testAuthFileManager()
    {
        $response = $this->withSession(['admin' => 1])->get('/fastleo/filemanager');
        $response->assertStatus(200);
    }
}
