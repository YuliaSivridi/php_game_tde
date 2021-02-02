<?php
require_once 'inccnt_tde.php';
require_once 'inccls_tde.php';
require_once 'inclng_tde.php';

// send telegram request
function trequest($method, $inputarray) {
    global $bottoken;
    $inputstring = http_build_query($inputarray, null, '&', PHP_QUERY_RFC3986);
    $options = ['http' => ['method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded\r\n',
            'ignore_errors' => true,
            'content' => $inputstring]];
    $request='https://api.telegram.org/bot'.$bottoken.'/'.$method;
    $context = stream_context_create($options);
    $answer = file_get_contents($request, false, $context);
    return $answer;
}

// get user from db
function select_user($dblink, $tbl_name, $chat_id) {
	$query_usr = "select * from ".$tbl_name." where chat_id='".$chat_id."'";
	$result_usr = mysqli_query($dblink, $query_usr);
    return $result_usr;
}

// players & matches -> insert to db
function update_all($dblink, $tbl_name, $chat_id, $kbd_id, $result_id, $players, $matches) {
	$query_upd = "update ".$tbl_name." set 
		kbd_id='".$kbd_id."',
		result_id='".$result_id."',
		players='".json_encode($players, JSON_UNESCAPED_UNICODE)."',
		matches='".json_encode($matches, JSON_UNESCAPED_UNICODE)."' 
		where chat_id='".$chat_id."'";
	$result_upd = mysqli_query($dblink, $query_upd);
}

// move player to next place in correct grid
function move_player($matches, $gridMatch, $crnt_match, $crnt_place, $players, $p) {
	foreach ($matches as $m => $match) {
		if (!isset($match->gridMatch)) $match->gridMatch = $gridMatch;
		if (($match->gridMatch === $gridMatch) && (!is_object($match->players[0]) || !is_object($match->players[1]))) {
			$pm = (!is_object($match->players[0])) ? 0 : 1;
			$matches[$m]->players[$pm] = new MatchPlayer($p, $players[$p]->name);
			// remember in current place where is next place
			if ($crnt_match >= 0) $matches[$crnt_match]->players[$crnt_place]->next = [$m, $pm];
			break;
		}
	} return $matches;
}

// make keyboard
function draw_keyboard($matches, $lm, $lp) {
	foreach ($matches as $m => $match) {
		$ibtns = [];
		if (is_array($match->players)) {
			// matches numbers
			$ibtns[] = ['text' => (($match->gridMatch === true) ? '🟩' : '🟥').'#'.str_pad($m, 2, '0', STR_PAD_LEFT), 'callback_data' => $m.'-x-nothing'];
			foreach ($match->players as $p => $player) {
				switch ($player->state) {
					case 0: $prefix = '⚪️'; break; // not played
					case 1: $prefix = '🟢'; break; // winner
					case 2: $prefix = '🔴'; break; // loser
					case 3: $prefix = '⚫'; break; // dropout
				} // catch callback: 'replay' for last played, 'nothing' for played, 'normal' for who can play
				$btnclb = (($m == $lm) && ($p == $lp)) ? '-replay' : ((($player->state == 0) && (count($match->players) == 2)) ? '-normal' : '-nothing');
				$ibtns[] = ['text' => $prefix.' '.$player->name, 'callback_data' => $m.'-'.$p.$btnclb];
			}
		} if (count($ibtns) > 1) $ikbd[] = $ibtns;
	} return $ikbd;
}

// is it close to the end of game?
function howManyAlive($players) {
	$counter = 0;
	foreach ($players as $p => $player)
		if ($player->losses < 2)
			$counter++;
	return $counter;
}

// show result table at the end of the game
function showResults($winner, $loser, $players, $title) {
	$place = 1; $played = [];
	$summary = '🏆 '.$title;
	$summary .= "\n".'🥇 *'.$winner->name.'*'; $place++;
	$summary .= "\n".'🥈 *'.$loser->name.'*'; $place++;
	foreach ($players as $p => $player) {
		if (!is_array($played[$player->played])) $played[$player->played] = [];
		$played[$player->played][] = $player->name;
	} krsort($played);
	foreach ($played as $p => $names) {
		if (!is_array($names)) continue;
		if ($p == 2) continue;
		$first = true;
		foreach ($names as $n => $name) {
			if ($name == $winner->name || $name == $loser->name) continue;
			if ($first) {
				$first = false;
				$summary .= "\n";
				if ($place == 3) { $summary .= '🥉'; $place++; }
				else { $summary .= ' #'.($place++); }
			} $summary .= ' _'.($name).'_ ';
		}
	} return $summary;
}

// get user request
$content = file_get_contents('php://input');
$input = json_decode($content, TRUE);
$dblink = mysqli_connect($dbhost, $dbuser, $dbpswd, $dbname);

// user send msg
if (($input['message']) != null) {
	$chat_id = $input['message']['chat']['id'];
	$user_lang = $input['message']['from']['language_code'];
	$user_msg = trim($input['message']['text']);

	$result_usr = select_user($dblink, $tbl_name, $chat_id);
	// user new -> insert to db
	if (mysqli_num_rows($result_usr) <= 0) {
		$user_lang = (array_key_exists($user_lang, $lang)) ? $user_lang : 'en';
		$query_ins = "insert into ".$tbl_name." (chat_id, user_lang, user_name) values ('".$chat_id."', '".$user_lang."', 
			'".$input['message']['from']['first_name']." ".$input['message']['from']['last_name']."')";
		$result_ins = mysqli_query($dblink, $query_ins);
		$answer = trequest('sendMessage', ['chat_id' => $chat_id, 'text' => $lang[$user_lang]['hi1'].$input['message']['from']['first_name'].$lang[$user_lang]['hi2']]);

	// user exists -> play
	} else {
		// all from db -> players & matches
		$row = mysqli_fetch_assoc($result_usr);
		$kbd_id = $row['kbd_id']; $user_lang = $row['user_lang'];
		$user_lang = (array_key_exists($user_lang, $lang)) ? $user_lang : 'en';
		$lkeys = array_keys($lang); $flag_lang = [];
		foreach ($lkeys as $lkey) $flag_lang[] = $flags[$lkey].' '.$lkey;
		$players = json_decode($row['players'], false, 512, JSON_UNESCAPED_UNICODE);
		$matches = json_decode($row['matches'], false, 512, JSON_UNESCAPED_UNICODE);

		switch ($user_msg) {
			case '/help':
				$answer = trequest('sendMessage', ['chat_id' => $chat_id, 'text' => $lang[$user_lang]['help']]);
				break;

			case '/lang':
				$answer = trequest('sendMessage', ['chat_id' => $chat_id, 'text' => $lang[$user_lang]['lang_ask'],
					'reply_markup' => json_encode(['keyboard' => [$flag_lang], 'resize_keyboard' => true])]);
				break;

			// msg -> chosen language
			case in_array($user_msg, $flag_lang): {
				$l = explode(' ', $user_msg);
				$user_lang = $l[1];
				$query_lng = "update ".$tbl_name." set user_lang='".$user_lang."' where chat_id='".$chat_id."'";
				$result_lng = mysqli_query($dblink, $query_lng);
				$answer = trequest('sendMessage', ['chat_id' => $chat_id, 'text' => $lang[$user_lang]['lang_ok'],
					'reply_markup' => json_encode(['remove_keyboard' => true])]);
				break;
			}

			case '/newgame':
				// clear db for new match
				$query_cln = "update ".$tbl_name." set kbd_id=0, result_id=0, players='', matches='' where chat_id='".$chat_id."'";
				$result_cln = mysqli_query($dblink, $query_cln);
				$answer = trequest('sendMessage', ['chat_id' => $chat_id, 'text' => $lang[$user_lang]['new']]);
				break;

			// msg -> new player name
			default:
				// name alreay used
				$badletters = ['\\', '\"', '\'', '`'];
				$name = str_replace($badletters, "", $user_msg);
				$already = false;
				if (is_array($players))
					foreach ($players as $p => $player)
						if ($player->name == $name)
							$already = true;
				if ($already) {
					$answer = trequest('sendMessage', ['chat_id' => $chat_id,
						'text' => $lang[$user_lang]['alr1'].$name.$lang[$user_lang]['alr2'],
						'parse_mode' => 'Markdown']);

				// new player -> players
				} else {
					$player = new Player($name);
					$players[] = $player;
					$p = count($players) - 1;

					// rebuild matches table
					for ($m = 0; $m < (2 * count($players) - 1); $m++)
						if (!is_object($matches[$m])) $matches[$m] = new Match();
					$matches = move_player($matches, true, -1, -1, $players, $p);

					// make keyboard
					$answer = trequest('deleteMessage', ['chat_id' => $chat_id, 'message_id' => $kbd_id]);
					$ikbd = draw_keyboard($matches, -1, -1);
					$answer = trequest('sendMessage', ['chat_id' => $chat_id,
						'text' => $lang[$user_lang]['add1'].$user_msg.$lang[$user_lang]['add2'],
						'parse_mode' => 'Markdown',
						'reply_markup' => json_encode(['inline_keyboard' => $ikbd])]);
					$tresponse = json_decode($answer, true);
					$kbd_id = $tresponse['result']['message_id'];
					// players & matches -> insert to db
					update_all($dblink, $tbl_name, $chat_id, $kbd_id, 0, $players, $matches);
				}
		}
	} mysqli_free_result($result_usr);

// user press button
} else if ($input['callback_query'] != null) {
	$cb_id = $input['callback_query']['id'];
	$chat_id = $input['callback_query']['message']['chat']['id'];
	$cb_data = $input['callback_query']['data'];
	// switch off clock on button
	$answer = trequest('answerCallbackQuery', ['callback_query_id' => $cb_id]);

	$mp = explode('-', $cb_data);
	if ($mp[2] != 'nothing') {
		// all from db -> players & matches
		$result_usr = select_user($dblink, $tbl_name, $chat_id);
		$row = mysqli_fetch_assoc($result_usr);
		$kbd_id = $row['kbd_id']; $user_lang = $row['user_lang'];
		$user_lang = (array_key_exists($user_lang, $lang)) ? $user_lang : 'en';
		$result_id = $row['result_id'];
		$players = json_decode($row['players'], false, 512, JSON_UNESCAPED_UNICODE);
		$matches = json_decode($row['matches'], false, 512, JSON_UNESCAPED_UNICODE);
		mysqli_free_result($result_usr);

		// who are winner and loser
		$m = $mp[0]; $mpw_id = $mp[1]; $mpl_id = ($mpw_id == 0) ? 1 : 0;
		$pw_id = $matches[$m]->players[$mpw_id]->id;
		$pl_id = $matches[$m]->players[$mpl_id]->id;
		// update players
		if ($mp[2] == 'normal') {
			$players[$pw_id]->played++;
			$players[$pl_id]->played++;
			$players[$pl_id]->losses++;
		} else {
			$players[$pw_id]->losses--;
			$players[$pl_id]->losses++;
		}
		// update matches
		$matches[$m]->players[$mpw_id]->state = 1;
		$matches[$m]->players[$mpl_id]->state = ($players[$pl_id]->losses == 1) ? 2 : 3;

		if ($mp[2] == 'replay') { // delete next step
			if (isset($matches[$m]->players[$mpl_id]->next[0])) unset($matches[$matches[$m]->players[$mpl_id]->next[0]]->players[$matches[$m]->players[$mpl_id]->next[1]]);
			if (isset($matches[$m]->players[$mpw_id]->next[0])) unset($matches[$matches[$m]->players[$mpw_id]->next[0]]->players[$matches[$m]->players[$mpw_id]->next[1]]);
		}

		if (howManyAlive($players) < 2) { // showResults
			$answer = trequest('deleteMessage', ['chat_id' => $chat_id, 'message_id' => $result_id]);
			$summary = showResults($players[$pw_id], $players[$pl_id], $players, $lang[$user_lang]['res']);
			$answer = trequest('sendMessage', ['chat_id' => $chat_id, 
				'text' => $summary,
				'parse_mode' => 'Markdown']);
			$tresponse = json_decode($answer, true);
			$result_id = $tresponse['result']['message_id'];
		} else { // move loser & winner
			$gridMatch = (howManyAlive($players) < 3);
			// move loser to (true) winners in superfinal or to (false) losers
			if ($players[$pl_id]->losses < 2) {
				$matches = move_player($matches, $gridMatch, $m, $mpl_id, $players, $pl_id);
			} // move winner of losers to (true) winners or to (false) losers
			if ($players[$pw_id]->losses > 0) {
				$matches = move_player($matches, $gridMatch, $m, $mpw_id, $players, $pw_id);
			} else { // move winner to winners
				$matches = move_player($matches, true, $m, $mpw_id, $players, $pw_id);
			}
		}

		// make keyboard
		$answer = trequest('deleteMessage', ['chat_id' => $chat_id, 'message_id' => $kbd_id]);
		$ikbd = draw_keyboard($matches, $m, $mpl_id);
		$answer = trequest('sendMessage', ['chat_id' => $chat_id,
			'text' => $lang[$user_lang]['win1'].$players[$pw_id]->name.$lang[$user_lang]['win2'].$m.$lang[$user_lang]['win3'],
			'parse_mode' => 'Markdown',
			'reply_markup' => json_encode(['inline_keyboard' => $ikbd], JSON_UNESCAPED_UNICODE)]);
		$tresponse = json_decode($answer, true);
		$kbd_id = $tresponse['result']['message_id'];
		// players & matches -> insert to db
		update_all($dblink, $tbl_name, $chat_id, $kbd_id, $result_id, $players, $matches);
	}
} mysqli_close($dblink); ?>