<?php

function loadData(): array {
    if (!file_exists('storage.json')) {
        file_put_contents('storage.json', json_encode([]));
    }
    return json_decode(file_get_contents('storage.json'), true);
}

function saveData(array $data): void {
    file_put_contents('storage.json', json_encode($data, JSON_PRETTY_PRINT));
}

function addGame(string $player, string $cpu, string $result): void {
    $data = loadData();
    $data[] = [
        'date' => date('Y-m-d H:i:s'),
        'player' => $player,
        'cpu' => $cpu,
        'result' => $result
    ];
    saveData($data);
}

function getCPUChoice(): string {
    $choices = ['pierre', 'feuille', 'ciseau'];
    return $choices[array_rand($choices)];
}

function getWinner(string $player, string $cpu): string {
    if ($player === $cpu) return 'Égalité';
    if (
        ($player === 'pierre' && $cpu === 'ciseau') ||
        ($player === 'feuille' && $cpu === 'pierre') ||
        ($player === 'ciseau' && $cpu === 'feuille')
    ) return 'Victoire';
    return 'Défaite';
}

function readInput(string $prompt): string {
    echo $prompt;
    return trim(fgets(STDIN));
}