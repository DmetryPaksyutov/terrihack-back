<?php

namespace App\Services\AI;

use Illuminate\Support\Str;
use OpenAI\Client;

class AiService
{
    public function __construct(
        public Client $openAi,
        public string $modelName,
    ) {

    }

    public function getQueryParamsByUserQueryString(string $query, array $paramsRules)
    {
        assert(!empty($paramsRules));
        $params = json_encode($paramsRules);
        $keys = collect($paramsRules)->keys();

        $response = $this->openAi->chat()->create([
            'model' => $this->modelName,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => <<<PROMPT
                        Есть следующие query параметры и правила: $params;
                        существительное скорее всего профессия
                        опытный = 3 года минимум
                        Дай ответ в формате JSON заполнив его примерно так: [{"remote": true}, {"minAge": 50}]
                    PROMPT
                ],
                [
                    'role' => 'user',
                    'content' => $query,
                ],
            ],
            'max_tokens' => 100,
        ]);

        $content = Str::swap([
            '```' => '',
            'json' => '',
        ], $response->choices[0]->message->content);

        $content = json_decode($content, true);

        if (!$content) {
            return false;
        }

        return collect($content)
            ->filter(fn($item) => $keys->search(array_keys($item)[0]) !== false)
            ->values()
            ->collapse()
            ->toArray();
    }
}