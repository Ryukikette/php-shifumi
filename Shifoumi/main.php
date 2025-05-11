<?php
require_once 'functions.php';

function showMenu(): void {
    echo "\n===== PIERRE - FEUILLE - CISEAU =====\n";
    echo "1. Nouvelle partie\n";
    echo "2. Historique\n";
    echo "3. Statistiques\n";
    echo "4. Quitter\n";
    echo "Choix : ";
}

function newGame(): void {
    while (true) {
        echo "\n--- Nouvelle partie ---\n";
        echo "Entrez votre choix (pierre, feuille, ciseau) ou 'retour' pour revenir : ";
        $player = trim(fgets(STDIN));

        if ($player === 'retour') return;
        if (!in_array($player, ['pierre', 'feuille', 'ciseau'])) {
            echo "Choix invalide.\n";
            continue;
        }

        $cpu = getCPUChoice();
        $result = getWinner($player, $cpu);
        addGame($player, $cpu, $result);

        echo "Vous : $player | CPU : $cpu\n";
        echo "Résultat : $result\n";

        $again = readInput("Rejouer ? (o/n) : ");
        if (strtolower($again) !== 'o') return;
    }
}

function showHistory(): void {
    $data = loadData();
    if (empty($data)) {
        echo "\nAucune partie enregistrée.\n";
        return;
    }

    echo "\n--- Historique ---\n";
    foreach ($data as $i => $entry) {
        echo ($i+1) . ". {$entry['date']} - Joueur: {$entry['player']} | CPU: {$entry['cpu']} | Résultat: {$entry['result']}\n";
    }
}

function showStats(): void {
    $data = loadData();
    $total = count($data);
    if ($total === 0) {
        echo "\nAucune donnée pour les statistiques.\n";
        return;
    }

    $wins = $draws = $losses = 0;
    $hands = ['pierre' => 0, 'feuille' => 0, 'ciseau' => 0];
    $winByHand = ['pierre' => 0, 'feuille' => 0, 'ciseau' => 0];

    foreach ($data as $d) {
        $hands[$d['player']]++;
        if ($d['result'] === 'Victoire') {
            $wins++;
            $winByHand[$d['player']]++;
        } elseif ($d['result'] === 'Égalité') {
            $draws++;
        } else {
            $losses++;
        }
    }

    echo "\n--- Statistiques ---\n";
    echo "Total de parties : $total\n";
    echo "Victoires : $wins (" . round($wins / $total * 100, 2) . "%)\n";
    echo "Égalités : $draws\n";
    echo "Défaites : $losses\n";

    arsort($hands);
    $mostUsed = array_key_first($hands);
    echo "Main la plus utilisée : $mostUsed\n";

    foreach ($winByHand as $hand => $winCount) {
        $played = $hands[$hand];
        $rate = $played ? round($winCount / $played * 100, 2) : 0;
        echo "Taux de victoire ($hand) : $rate%\n";
    }
}

while (true) {
    showMenu();
    $choice = trim(fgets(STDIN));
    switch ($choice) {
        case '1':
            newGame();
            break;
        case '2':
            showHistory();
            break;
        case '3':
            showStats();
            break;
        case '4':
            echo "À bientôt !\n";
            exit;
        default:
            echo "Choix invalide.\n";
    }
}