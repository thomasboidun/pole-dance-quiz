<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SubscribeControllerTest extends WebTestCase
{
  /**
   * 
   * for test the subscribe page
   * 
   * scenario:
   * 
   * given: an user is on the page
   * 
   * and || but
   * 
   * whene: an user go to the page
   * 
   * then: the page load and return the status code 200
   * 
   * and: the main of document contains a h1 title with content text 'Subscribe!'
   * 
   * and: the main of document contains a form with id attribute equals 'subscribe-form'
   */
  public function test_the_subscribe_page()
  {
    $client = static::createClient();

    $crawler = $client->request('GET', '/subscribe'); // get the HTML DOM

    $this->assertEquals(200, $client->getResponse()->getStatusCode());

    $this->assertSelectorTextContains('html main.main h1', 'Subscribe now!');

    // $form = $crawler->filter('.main form#subscribe-form');
  }
}