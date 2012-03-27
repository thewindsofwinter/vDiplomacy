<?php
/*
    Copyright (C) 2004-2010 Kestas J. Kuliukas

	This file is part of webDiplomacy.

    webDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    webDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('IN_CODE') or die('This script can not be run by itself.');

/**
 * @package Base
 * @subpackage Forms
 */
?>
<div class="content-bare content-board-header content-title-header">
<div class="pageTitle barAlt1">
	Create a new game
</div>
<div class="pageDescription barAlt2">
Start a new game; you decide the name, how long it runs, and how much it's worth.
</div>
</div>
<div class="content content-follow-on">
<form method="post">
<ul class="formlist">

	<li class="formlisttitle">
		Name:
	</li>
	<li class="formlistfield">
		<input type="text" name="newGame[name]" value="" size="30">
	</li>
	<li class="formlistdesc">
		The name of your game
	</li>

	<li class="formlisttitle">
		Phase length: (5 minutes - 10 days)
	</li>
	<li class="formlistfield">
		<select name="newGame[phaseMinutes]" onChange="document.getElementById('wait').selectedIndex = this.selectedIndex">
		<?php
			$phaseList = array(5, 10, 15, 20, 30,
				60, 120, 240, 360, 480, 600, 720, 840, 960, 1080, 1200, 1320,
				1440, 2160, 2880, 4320, 5760, 7200, 8640, 10080, 14400, 1440+60, 2880+60*2);

			foreach ($phaseList as $i) {
				$opt = libTime::timeLengthText($i*60);

				print '<option value="'.$i.'"'.($i==1440 ? ' selected' : '').'>'.$opt.'</option>';
			}
		?>
		</select>
	</li>
	<li class="formlistdesc">
		The maximum number of hours allowed for players to discuss and enter orders each phase.<br />
		Longer phase hours means more time to make careful decisions and negotiations, but makes a game take longer. Shorter
		phase hours results in a faster game, but requires that players in the game are available to check the game frequently.<br /><br />

		<strong>Default:</strong> 24 hours/1 day
	</li>

	<li class="formlisttitle">
		Bet size: (2<?php print libHTML::points(); ?> -
			<?php print $User->points.libHTML::points(); ?>)
	</li>
	<li class="formlistfield">
		<input type="text" name="newGame[bet]" size="7" value="<?php print $formPoints ?>" />
	</li>
	<li class="formlistdesc">
		The bet required to join this game. This is the amount of points that all players, including you,
		must put into the game's "pot" (<a href="points.php" class="light">read more</a>).<br /><br />

		<strong>Default:</strong> <?php print $defaultPoints.libHTML::points(); ?>
	</li>
</ul>

<div class="hr"></div>

<div id="AdvancedSettingsButton">
<ul class="formlist">
	<li class="formlisttitle">
		<a href="#" onclick="$('AdvancedSettings').show(); $('AdvancedSettingsButton').hide(); return false;">
		Open Advanced Settings
		</a>
	</li>
	<li class="formlistdesc">
		Advanced settings allowing extra customization of games for seasoned players, allowing
		different map choices, alternate rules, and non-standard timing options.<br /><br />

		The default settings are fine for <strong>new players</strong>.
	</li>
</ul>
</div>

<div id="AdvancedSettings" style="<?php print libHTML::$hideStyle; ?>">

<h3>Advanced settings</h3>

<ul class="formlist">
<?php
if( count(Config::$variants)==1 )
{
	foreach(Config::$variants as $variantID=>$variantName) ;

	$defaultVariantName=$variantName;

	print '<input type="hidden" name="newGame[variantID]" value="'.$variantID.'" />';
}
else
{
?>
	<li class="formlisttitle">Variant map/rules:</li>
	<li class="formlistfield">
	<script type="text/javascript">
	
	function setExtOptions(i){
		document.getElementById('countryID').options.length=0;
		switch(i)
		{
			<?php
			$checkboxes=array();
			$first='';
			foreach(Config::$variants as $variantID=>$variantName)
			{
				$Variant = libVariant::loadFromVariantName($variantName);
				$checkboxes[$variantName] = '<option value="'.$variantID.'"'.(($first=='')?' selected':'').'>'.$variantName.'</option>';
				if($first=='') {
					$first='"'.$variantID.'"';
					$defaultName=$variantName;
				}
				print "case \"".$variantID."\":\n";
				print 'document.getElementById(\'desc\').innerHTML = "<a class=\'light\' href=\'variants.php?variantID='.$variantID.'\'>'.$Variant->fullName.'</a><hr style=\'color: #aaa\'>'.$Variant->description.'";'."\n";		
				print "document.getElementById('countryID').options[0]=new Option ('Random','0');";
				for ($i=1; $i<=count($Variant->countries); $i++)
					print "document.getElementById('countryID').options[".$i."]=new Option ('".$Variant->countries[($i -1)]."', '".$i."');";
				print "break;\n";		
			}	
			ksort($checkboxes);	
			?>	
		}

	}
	</script>
	
	<table><tr>
		<td	align="left" width="0%">
			<select name="newGame[variantID]" onChange="setExtOptions(this.value)">
			<?php print implode($checkboxes); ?>
			</select> </td>
		<td align="left" width="100%">
			<div id="desc" style="border-left: 1px solid #aaa; padding: 5px;"></div></td>
	</tr></table>
	</li>
	<li class="formlistdesc">
		Select which type of Diplomacy game you would like to play from a selection of maps and alternate rule settings
		available on this server.<br /><br />

		Click any of the variant names to view the details on the variants page.<br /><br />

		<strong>Default:</strong> <?php print $defaultName;?>
	</li>
<?php
}
?>
	<li class="formlisttitle">Country assignment:</li>
	<li class="formlistfield">
		<select id="countryID" name="newGame[countryID]">
		</select>
	</li>

	<li class="formlistdesc">
		Random distribution of each country, or players pick their country (gamecreator get's the selected country).<br /><br />
		<strong>Default:</strong> Random
	</li>
	
	<script type="text/javascript">
	setExtOptions(<?php print $first;?>);
	</script>
	
	<li class="formlisttitle">Pot type:</li>
	<li class="formlistfield">
		<input type="radio" name="newGame[potType]" value="Points-per-supply-center" checked > Points-per-supply-center<br />
		<input type="radio" name="newGame[potType]" value="Winner-takes-all"> Winner-takes-all
	</li>
	<li class="formlistdesc">
		An expert setting: Should the winnings be split up according to who has the most supply centers, or should the winner
		get everything (<a href="points.php#ppscwta" class="light">read more</a>).<br /><br />

		<strong>Default:</strong> Points-per-supply-center
	</li>

	<li class="formlisttitle">
		Anonymous players:
	</li>
	<li class="formlistfield">
		<input type="radio" name="newGame[anon]" value="No" checked>No
		<input type="radio" name="newGame[anon]" value="Yes">Yes
	</li>
	<li class="formlistdesc">
		If enabled players will not see each others names and user information, players are anonymous until the game ends.<br /><br />

		<strong>Default:</strong> No, players aren't anonymous
	</li>

	<li class="formlisttitle">
		Disable in-game messaging:
	</li>
	<li class="formlistfield">
		<input type="radio" name="newGame[pressType]" value="Regular" checked>Allow all
		<input type="radio" name="newGame[pressType]" value="PublicPressOnly">Global messages only, no private chat/press
		<input type="radio" name="newGame[pressType]" value="NoPress">No in-game messaging
	</li>
	<li class="formlistdesc">
		Disable some types of messaging; allow only global in-game messages, or allow no in-game messages.

		<br /><br /><strong>Default:</strong> Allow all
	</li>

	<li class="formlisttitle">
		Joining pre-game period length: (5 minutes - 10 days)
	</li>
	<li class="formlistfield">
		<select id="wait" name="newGame[joinPeriod]">
		<?php
			foreach ($phaseList as $i) {
				$opt = libTime::timeLengthText($i*60);

				print '<option value="'.$i.'"'.($i==1440 ? ' selected' : '').'>'.$opt.'</option>';
			}
		?>
		</select>
	</li>
	<li class="formlistdesc">
		The amount of time to wait for people to join. For 5 minute games you may want to give players longer than 5 minutes to join.

		<br /><br /><strong>Default:</strong> The same as phase length
	</li>

	<li class="formlisttitle">
		Rating requirements:
	</li>
	<li class="formlistfield">
		<b>Min Rating: </b><select name="newGame[minRating]">
			<option value=00 selected>none</option>
			<option value=90>A</option>
			<option value=80>B</option>
			<option value=70>C</option>
			<option value=60>D</option>
			<option value=50>E</option>
			</select> / 
		<b>Min Phases: </b><input type="text" name="newGame[minPhases]" value="0" size="5"> / 
		<b>Max CDs: </b><input type="text" name="newGame[maxLeft]" value="99" size="2">
	</li>
	<li class="formlistdesc">
		You can set some requirements that the players for your game need to fulfill.
		
		<ul>
			<li><b>Minimum Rating:</b> Here you can choose the minimum Reliability Rating a player must have in order to join your game. (ie. 50 = only players who have a Reliability Rating of 50+ can join)</li>
			<li><b>Minimum Phases:</b> Here you can choose how many phases a player must have played in order to join your game. (ie. 300 = only players who have played 300+ phases on vDip can join)</li>
			<li><b>Maximum CDs:</b> Here you can set the maximum amount of CDs a player may have had before they are not allowed to join your game (ie. 15 = only players who have resigned/left a game 14 or less times can join your game)</li>
		</ul>
		This might lead to people not able to join your games, so choose your options wisely.<br /><br />
		<strong>Default:</strong> No restrictions:
	</li>

	<li class="formlisttitle">
		Alternate winning conditions:
	</li>
	<li class="formlistfield"> 
		<b>Target SCs: </b><input type="text" name="newGame[targetSCs]" size="4" value="0" /> (0 = default)<br>
		<b>Max. turns: </b><input type="text" name="newGame[maxTurns]" size="4" value="0" /> (4 < maxTurns < 200)
	</li>
	<li class="formlistdesc">
		This setting lets you limit how many turns are played and/or how many SCs need to be conquered before a winner is declared.
		Please check the variant-description for infomation about the average turns or the default SCs for a win.<br />
		The winning player is decided by who has the most SCs after that turn's diplomacy phase.
		If 2 or more player have the same SCs at the end of the game, the game checks for the turn before, and so on.
		If player's SC counts are the same throughout the whole game the winner is decided at random.
		<br />A value of "0" (the default) ends the game as usual, as soon as one player reach the default target SCs.
		<br /><br /><strong>Default:</strong> 0 (no fixed game duration / default number of SCs needed)
	</li>

	<li class="formlisttitle">
		<img src="images/icons/lock.png" alt="Private" /> Password protect (optional):
	</li>
	<li class="formlistfield">
		<ul>
			<li>Password: <input type="password" name="newGame[password]" value="" size="30" /></li>
			<li>Confirm: <input type="password" name="newGame[passwordcheck]" value="" size="30" /></li>
		</ul>
	</li>
	<li class="formlistdesc">
		<strong>This is optional.</strong> If you set this only people who know the password will be able to join.<br /><br />

		<strong>Default:</strong> No password set
	</li>
</ul>

</div>

<div class="hr"></div>

<p class="notice">
	<input type="submit" class="form-submit" value="Create">
</p>
</form>
