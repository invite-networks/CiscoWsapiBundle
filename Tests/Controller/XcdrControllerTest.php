<?php
/*
 * This file is part of the Invite Wsapi Bundle
 *
 * The bundle provides a mapping to Cisco's IOS UC Gateway Api.
 *
 * (c) Invite Networks Inc. <info@invitenetworks.com>
 *
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace Invite\Bundle\Cisco\WsapiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * XcdrControllerTest.
 *
 * @author Josh Whiting <josh@invitenetworks.com>
 */
class XcdrControllerTest extends WebTestCase
{

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/demo/hello/Fabien');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Hello Fabien")')->count());
    }

    public function testSecureSection()
    {
        $client = static::createClient();

        // goes to the secure page
        $crawler = $client->request('GET', '/demo/secured/hello/World');

        // redirects to the login page
        $crawler = $client->followRedirect();

        // submits the login form
        $form = $crawler->selectButton('Login')->form(array('_username' => 'admin', '_password' => 'adminpass'));
        $client->submit($form);

        // redirect to the original page (but now authenticated)
        $crawler = $client->followRedirect();

        // check that the page is the right one
        $this->assertCount(1, $crawler->filter('h1.title:contains("Hello World!")'));

        // click on the secure link
        $link = $crawler->selectLink('Hello resource secured')->link();
        $crawler = $client->click($link);

        // check that the page is the right one
        $this->assertCount(1, $crawler->filter('h1.title:contains("secured for Admins only!")'));
    }

}
