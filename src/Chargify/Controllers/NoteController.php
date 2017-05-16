<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/26/2016
 * Time: 5:00 PM
 */

namespace IvanCLI\Chargify\Controllers;


use Illuminate\Support\Facades\Cache;
use IvanCLI\Chargify\Models\Note;
use IvanCLI\Chargify\Traits\Curl;

class NoteController
{
    use Curl;

    protected $accessPoint;

    protected $apiDomain;

    public function __construct($accessPoint)
    {
        $this->accessPoint = $accessPoint;

        $this->apiDomain = config("chargify.{$this->accessPoint}.api_domain");
    }

    public function create($subscription_id, $fields)
    {
        return $this->__create($subscription_id, $fields);
    }

    public function update($subscription_id, $note_id, $fields)
    {
        return $this->__update($subscription_id, $note_id, $fields);
    }

    public function get($subscription_id, $note_id)
    {
        if (config('chargify.caching.enable') == true) {
            return Cache::remember("{$this->accessPoint}.subscriptions.{$subscription_id}.notes.{$note_id}", config('chargify.caching.ttl'), function () use ($subscription_id, $note_id) {
                return $this->___get($subscription_id, $note_id);
            });
        } else {
            return $this->___get($subscription_id, $note_id);
        }
    }

    public function allBySubscription($subscription_id)
    {
        if (config('chargify.caching.enable') == true) {
            return Cache::remember("{$this->accessPoint}.subscriptions.{$subscription_id}.notes", config('chargify.caching.ttl'), function () use ($subscription_id) {
                return $this->__allBySubscription($subscription_id);
            });
        } else {
            return $this->__allBySubscription($subscription_id);
        }
    }

    private function __create($subscription_id, $fields)
    {
        $url = $this->apiDomain . "subscriptions/{$subscription_id}/notes.json";
        $data = array(
            "note" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $note = $this->_post($url, $data);
        if (isset($note->note)) {
            $note = $this->__assign($note->note);
            Cache::forget("{$this->accessPoint}.subscriptions.{$subscription_id}.notes");
        }
        return $note;
    }

    private function __update($subscription_id, $note_id, $fields)
    {
        $url = $this->apiDomain . "subscriptions/{$subscription_id}/notes/{$note_id}.json";
        $data = array(
            "note" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $note = $this->_put($url, $data);
        if (isset($note->note)) {
            $note = $this->__assign($note->note);
            Cache::forget("{$this->accessPoint}.subscriptions.{$subscription_id}.notes.{$note->id}");
            Cache::forget("{$this->accessPoint}.subscriptions.{$subscription_id}.notes");
        }
        return $note;
    }

    private function ___get($subscription_id, $note_id)
    {
        $url = $this->apiDomain . "subscriptions/{$subscription_id}/notes/{$note_id}.json";
        $note = $this->_get($url);
        if (isset($note->note)) {
            $note = $note->note;
            $output = $this->__assign($note);
            return $output;
        } else {
            return $note;
        }
    }

    private function __allBySubscription($subscription_id)
    {
        $url = $this->apiDomain . "subscriptions/{$subscription_id}/notes.json";
        $notes = $this->_get($url);
        if (is_array($notes)) {
            $notes = array_pluck($notes, 'note');
            $output = array();
            foreach ($notes as $note) {
                $output[] = $this->__assign($note);
            }
            return $output;
        } else {
            return $notes;
        }
    }

    private function __assign($input_note)
    {
        $note = new Note;
        foreach ($input_note as $key => $value) {
            if (property_exists($note, $key)) {
                $note->$key = $value;
            }
        }
        return $note;
    }

}