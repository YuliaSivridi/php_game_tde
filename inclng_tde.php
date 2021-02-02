<?php
$flags = ['en' => '🇬🇧', 'de' => '🇩🇪', 'fr' => '🇫🇷', 'pt-br' => '🇧🇷', 'uk' => '🇺🇦', 'ru' => '🇷🇺'];
$lang = ['en' => ['hi1' => 'Hello, ', 'hi2' => ' 😋 Send /newgame to start a game', 
	'new' => '🎮 New game begins! 🙂 Send player name', 
	'help' => "🔤 Send /lang to change language\n🎮 Send /newgame to start new game\n🙂 Send player name to add him to the game\nClick on a player in a pair to mark the winner", 
	'lang_ask' => '🔤 Choose a language', 'lang_ok' => '✅ Language chosen', 
	'alr1' => '😕 Player _', 'alr2' => '_ already in the game', 
	'add1' => '😃 Player _', 'add2' => '_ added!', 
	'win1' => 'The winner is _', 'win2' => '_ in #', 'win3' => ' match',
	'res' => 'Game Results'], 
	// Hello! Send /newgame to start a game // New game begins! Send player name
	// Send /lang to change language. Send /newgame to start new game. Send player name to add him to the game. Click on a player in a pair to mark the winner
	// Choose a language // Language chosen
	// Player already in the game // Player added // The winner is 0 in 0 match // Game Results

	'de' => ['hi1' => 'Hallo, ', 'hi2' => ' 😋 Schreibe /newgame um ein Spiel zu Beginnen', 
	'new' => '🎮 Neues Spiel beginnt! 🙂 Schreibe Spielername', 
	'help' => "🔤 Schreibe /lang, um die Sprache zu ändern\n🎮 Schreibe /newgame um ein neues Spiel zu beginnen\n🙂 Schreibe Spielername um Ihn zum Spiel hinzuzufügen\nKlicken Sie auf einen Spieler in einem Paar, um den Sieger zu markieren", 
	'lang_ask' => '🔤 Wähle eine Sprache', 'lang_ok' => '✅ Sprache gewählt', 
	'alr1' => '😕 Spieler _', 'alr2' => '_ ist bereits in einem Spiel', 
	'add1' => '😃 Spieler _', 'add2' => '_ hinzugefügt!', 
	'win1' => 'Der Sieger ist _', 'win2' => '_ im #', 'win3' => ' Match',
	'res' => 'Spielergebnisse'], 
	// Hallo! Schreibe /newgame um ein Spiel zu Beginnen // Neues Spiel beginnt! Schreibe Spielername
	// Schreibe /lang, um die Sprache zu ändern. Schreibe /newgame um ein neues Spiel zu beginnen. Schreibe Spielername um Ihn zum Spiel hinzuzufügen. Klicken Sie auf einen Spieler in einem Paar, um den Sieger zu markieren
	// Wähle eine Sprache // Sprache gewählt
	// Spieler ist bereits in einem Spiel // Spieler hinzugefügt // Der Sieger ist 0 im 0 Match // Spielergebnisse

	'fr' => ['hi1' => 'Salut, ', 'hi2' => ' 😋 Envoyer /newgame jeu pour démarrer un nouveau jeu', 
	'new' => '🎮 Un nouveau jeu commence! 🙂 Envoyer le nom du joueur', 
	'help' => "🔤 Envoyer /lang pour changer de langue\n🎮 Envoyer /newgame pour démarrer un nouveau jeu\n🙂 Envoyer le nom du joueur pour l'ajouter dans le jeu\nCliquez sur un joueur dans une paire pour marquer le gagnant", 
	'lang_ask' => '🔤 Choisissez une langue', 'lang_ok' => '✅ Langue choisie', 
	'alr1' => '😕 Joueur _', 'alr2' => '_ déjà en jeu', 
	'add1' => '😃 Joueur _', 'add2' => '_ ajouté!', 
	'win1' => 'Vainqueur joueur _', 'win2' => '_ en #', 'win3' => ' match',
	'res' => 'Résultats du jeu'], 
	// Salut! Envoyer /newgame jeu pour démarrer un nouveau jeu // Un nouveau jeu commence! Envoyer le nom du joueur
	// Envoyer /lang pour changer de langue. Envoyer /newgame pour démarrer un nouveau jeu. Envoyer le nom du joueur pour l'ajouter dans le jeu. Cliquez sur un joueur dans une paire pour marquer le gagnant
	// Choisissez une langue // Langue choisie
	// Joueur déjà en jeu // Joueur ajouté // Vainqueur joueur 0 en 0 match // Résultats du jeu

	'pt-br' => ['hi1' => 'Olá, ', 'hi2' => ' 😋 Envie /newgame para começar um jogo', 
	'new' => '🎮 Novo jogo começou! 🙂 Envie nome do jogador', 
	'help' => "🔤 Envie /lang para alterar o idioma\n🎮 Envie /newgame para começar um novo jogo\n🙂 Envie o nome do jogador para adiciona-lo ao jogo\nClique em um jogador em um par para marcar o vencedor", 
	'lang_ask' => '🔤 Escolha um idioma', 'lang_ok' => '✅ Idioma escolhido', 
	'alr1' => '😕 Jogador _', 'alr2' => '_ já esta no jogo', 
	'add1' => '😃 Jogador _', 'add2' => '_ adicionado!', 
	'win1' => 'O vencedor é _', 'win2' => '_ no #', 'win3' => ' partida',
	'res' => 'Resultados do Jogo'], 
	// Olá! Envie /newgame para começar um jogo // Novo jogo começou! Envie nome do jogador
	// Envie /lang para alterar o idioma. Envie /newgame para começar um novo jogo. Envie o nome do jogador para adiciona-lo ao jogo. Clique em um jogador em um par para marcar o vencedor
	// Escolha um idioma // Idioma escolhido
	// Jogador já esta no jogo // Jogador adicionado // O vencedor é _ no _ partida // Resultados do Jogo

	'uk' => ['hi1' => 'Привіт, ', 'hi2' => ' 😋 Надішли /newgame щоб розпочати нову гру', 
	'new' => "🎮 Нова гра розпочата! 🙂 Надішли ім'я гравця", 
	'help' => "🔤 Надішли /lang для зміни мови\n🎮 Надішли /newgame щоб розпочати нову гру\n🙂 Надішли ім'я гравця, щоб добавити його в гру\nНажми на гравця в парі щоб відмітити переможця", 
	'lang_ask' => '🔤 Виберіть мову', 'lang_ok' => '✅ Мова вибрана', 
	'alr1' => '😕 Гравець _', 'alr2' => '_ уже в грі', 
	'add1' => '😃 Гравець _', 'add2' => '_ доданий!', 
	'win1' => 'Переміг гравець _', 'win2' => '_ в #', 'win3' => ' матчі',
	'res' => 'Результати гри'], 
	// Привіт! Надішли /newgame щоб розпочати нову гру // Нова гра розпочата! Надішли ім'я гравця
	// Надішли /lang для зміни мови. Надішли /newgame щоб розпочати нову гру. Надішли ім'я гравця, щоб добавити його в гру. Нажми на гравця в парі щоб відмітити переможця
	// Виберіть мову // Мова вибрана
	// Гравець уже в грі // Гравець доданий // Переміг гравець 0 в 0 матчі // Результати гри

	'ru' => ['hi1' => 'Привет, ', 'hi2' => ' 😋 Отправь /newgame для начала новой игры', 
	'new' => '🎮 Новая игра начата! 🙂 Отправь имя игрока', 
	'help' => "🔤 Отправь /lang для смены языка\n🎮 Отправь /newgame для начала новой игры\n🙂 Отправь имя игрока, чтобы добавить его в игру\nНажми на игрока в паре чтобы отметить победителя", 
	'lang_ask' => '🔤 Выбери язык', 'lang_ok' => '✅ Язык выбран', 
	'alr1' => '😕 Игрок _', 'alr2' => '_ уже в игре', 
	'add1' => '😃 Игрок _', 'add2' => '_ добавлен!', 
	'win1' => 'Победитель игрок _', 'win2' => '_ в #', 'win3' => ' матче',
	'res' => 'Результаты игры']];
	// Привет! Отправь /newgame для начала новой игры // Новая игра начата! Отправь имя игрока
	// Отправь /lang для смены языка. Отправь /newgame для начала новой игры. Отправь имя игрока, чтобы добавить его в игру. Нажми на игрока в паре чтобы отметить победителя
	// Выбери язык // Язык выбран
	// Игрок уже в игре // Игрок добавлен // Победитель игрок 0 в 0 матче // Результаты игры
?>