<?php

namespace App;

use ScoutElastic\SearchRule;

class MySearchRule extends SearchRule
{
    /**
     * @inheritdoc
     */
    public function buildHighlightPayload()
    {
        //
        return [
      'fields' => [
          'title' => [
              'type' => 'plain'
          ]
      ]
  ];
    }

    /**
     * @inheritdoc
     */
    public function buildQueryPayload()
    {
        //
        return [
        'should' => [
            'match_phrase' => [
            // 'title' => $this->builder->query,
            'title' => $this->builder->query
            ]
        ]
    ];
    }
}
