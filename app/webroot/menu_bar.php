<?php
global $userdata;
// check if they are an ST
$gamemaster_link = "";

$supporter = "";
if ($userdata['is_admin']) {
    $supporter = <<<EOQ
<ul><li><a href='/support.php?action=manage'><span>Manage Supporters</span></a></li></ul>
EOQ;
}

if ($userdata['is_gm'] || $userdata['is_asst'] || $userdata['is_head']) {
    $gamemaster_link = <<<EOQ
<a href='/storyteller_index.php' target="_blank"><span>ST Tools</span></a>
<ul>
    <li><a href="view_sheet.php?action=st_view_xp"><span>Character Lookup</span></a></li>
    <li><a href="request.php?action=st_list"><span>Request Dashboard</span></a></li>
</ul>
EOQ;
}


// check if user is logged in
if ($user->data['user_id'] != ANONYMOUS) {
    $menu_bar = <<<EOQ
<div id='navmenu'>
<ul>
   <li class='active'><a href='/index.php'><span>Home</span></a></li>
   <li><span>The Setting</span>
      <ul>
         <li><a href='/wiki/index.php?n=City.City'><span>The City</span></a></li>
         <li><a href='/wiki/index.php?n=Changeling.Changeling'><span>Changeling</span></a>
         	<ul>
			   <li><a href='/wiki/index.php?n=Changeling.HouseRules'><span>Changeling House Rules</span></a></li>
			   <li><a href='/characters/cast/changeling'><span>Changeling Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Changeling'><span>Changeling Cast</span></a></li>
            </ul></li>
            <li><a href='/wiki/index.php?n=Geist.Geist'><span>Geist</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Geist.HouseRules'><span>Geist House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Geist.PlayerGuide'><span>Geist Player Guide</span></a></li>
			   <li><a href='/characters/cast/geist'><span>Geist Cast</span></a></li></ul></li>
         <li><a href='/wiki/index.php?n=Mage.Mage'><span>Mage</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Mage.HouseRules'><span>Mage House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Mage.PlayerGuide'><span>Mage Player Guide</span></a></li>
			   <li><a href='/characters/cast/mage'><span>Mage Cast</span></a></li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Mortal.Mortal'><span>Mortal</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Mortal.HouseRules'><span>Mortal House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Mortal.PlayerGuide'><span>Mortal Player Guide</span></a></li>
			   <li><a href='/characters/cast/mortal'><span>Mortal Cast</span></a></li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Vampire.Vampire'><span>Vampire</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Vampire.HouseRules'><span>Vampire House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Vampire.PlayerGuide'><span>Vampire Player Guide</span></a></li>
			   <li><a href='/characters/cast/vampire'><span>Vampire Cast</span></a></li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Werewolf.Werewolf'><span>Werewolf</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Werewolf.HouseRules'><span>Werewolf House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Werewolf.PlayerGuide'><span>Werewolf Player Guide</span></a></li>
			   <li><a href='/characters/cast/werewolf'><span>Werewolf Cast</span></a></li>
            </ul></li>
            <li><a href=''><span>Crossover Sub-venues</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Whitefield.Whitefield'><span>Whitefield University</span></a>
			   <ul>
               <li><a href='/wiki/index.php?n=Whitefield.HouseRules'><span>Whitefield House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Whitefield.PlayerGuide'><span>Whitefield Player Guide</span></a></li>
            </ul>
			   </li>
            </ul></li>
         <li><a href='/characters/cast/'><span>The Cast</span></a></li>
      </ul>
   </li>
   <li><span>Game Guide</span>
      <ul>
         <li><a href='/wiki/index.php?n=GameRef.HouseRules'><span>House Rules</span></a>
            <ul>
               <li><a href='/wiki/index.php?n=GameRef.CrossoverErrata'><span>Crossover Errata</span></a></li>
            </ul>
         </li>
         <li><a href='/wiki/index.php?n=GameRef.CharacterCreation'><span>Character Creation</span></a>
         <ul>
         <li><a href='/wiki/index.php?n=GameRef.SanctioningGuide'><span>Sanctioning Checklist</span></a></li>
         <li><a href='/wiki/index.php?n=GameRef.BookPolicy'><span>Book Policy</span></a></li>
         </ul></li>
         <li><a href='/wiki/index.php?n=GameRef.ExperienceGuide'><span>Experience Guide</span></a></li>
		 <li><a href='/wiki/index.php?n=GameRef.PoliciesandPractices'><span>Policies and Practices</span></a>
         <ul>
		 <li><a href='/wiki/index.php?n=GameRef.CrossoverPolicy'><span>Crossover Policy</span></a></li>
		 </ul></li>
      </ul>
   </li>
   <li><span>Tools</span>
      <ul>
         <li><a href='/wiki/index.php?n=GameRef.Help'><span>Help</span></a>
            <ul>
            	<li><a href='/wiki/index.php?n=GameRef.RequestSystemHelp'><span>Request System</span></a></li>
            	<li><a href='/wiki/index.php?n=GameRef.ChatHelp'><span>Chat Interface</span></a></li>
            </ul>
         </li>
         <li><a href='/support.php'><span>Site Supporter</span></a>
               $supporter
		 </li>
         <li><a href='/chat.php'><span>Character List</span></a></li>
         <li>$gamemaster_link</li>
      </ul>
   </li>
   <li><a href='/forum/index.php'><span>Forums</span></a></li>
   <li class='has-sub last'><span>Site Info</span>
      <ul>
	     <li><a href='/staff'><span>The Team</span></a></li>
         <li><a href='/wiki/index.php?n=GameRef.CodeOfConduct'><span>Code of Conduct</span></a>
            <ul>
               <li><a href='/wiki/index.php?n=GameRef.PersonalInformation'><span>Personal Information</span></a></li>
            </ul>
         </li>
         <li><a href='/wiki/index.php?n=GameRef.Disclaimer'><span>Disclaimer</span></a></li>
         <li><a href='/site_content.php?action=view&content_uid=links'><span>Links</span></a></li>
      </ul>
   </li>
</ul>
</div>
EOQ;
}
else {
$menu_bar .= <<<EOQ
<div id='navmenu'>
<ul>
   <li class='active'><a href='/index.php'><span>Home</span></a></li>
     <li><span>The Setting</span>
      <ul>
         <li><a href='/wiki/index.php?n=City.City'><span>The City</span></a></li>
         <li><a href='/wiki/index.php?n=Changeling.Changeling'><span>Changeling</span></a>
         	<ul>
			   <li><a href='/wiki/index.php?n=Changeling.HouseRules'><span>Changeling House Rules</span></a></li>
			   <li><a href='/characters/cast/changeling'><span>Changeling Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Changeling'><span>Changeling Cast</span></a></li>
            </ul></li>
            <li><a href='/wiki/index.php?n=Geist.Geist'><span>Geist</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Geist.HouseRules'><span>Geist House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Geist.PlayerGuide'><span>Geist Player Guide</span></a></li>
			   <li><a href='/characters/cast/geist'><span>Geist Cast</span></a></li></ul></li>
         <li><a href='/wiki/index.php?n=Mage.Mage'><span>Mage</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Mage.HouseRules'><span>Mage House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Mage.PlayerGuide'><span>Mage Player Guide</span></a></li>
			   <li><a href='/characters/cast/mage'><span>Mage Cast</span></a></li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Mortal.Mortal'><span>Mortal</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Mortal.HouseRules'><span>Mortal House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Mortal.PlayerGuide'><span>Mortal Player Guide</span></a></li>
			   <li><a href='/characters/cast/mortal'><span>Mortal Cast</span></a></li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Vampire.Vampire'><span>Vampire</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Vampire.HouseRules'><span>Vampire House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Vampire.PlayerGuide'><span>Vampire Player Guide</span></a></li>
			   <li><a href='/characters/cast/vampire'><span>Vampire Cast</span></a></li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Werewolf.Werewolf'><span>Werewolf</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Werewolf.HouseRules'><span>Werewolf House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Werewolf.PlayerGuide'><span>Werewolf Player Guide</span></a></li>
			   <li><a href='/characters/cast/werewolf'><span>Werewolf Cast</span></a></li>
            </ul></li>
            <li><a href=''><span>Crossover Sub-venues</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Whitefield.Whitefield'><span>Whitefield University</span></a>
			   <ul>
               <li><a href='/wiki/index.php?n=Whitefield.HouseRules'><span>Whitefield House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Whitefield.PlayerGuide'><span>Whitefield Player Guide</span></a></li>
            </ul>
			   </li>
            </ul></li>
         <li><a href='/characters/cast/'><span>The Cast</span></a></li>
      </ul>
   </li>
   <li><span>Game Guide</span>
      <ul>
         <li><a href='/wiki/index.php?n=GameRef.HouseRules'><span>House Rules</span></a>
            <ul>
			   <li><a href='/wiki/index.php?n=GameRef.CrossoverErrata'><span>Crossover Errata</span></a></li>
               <li><a href='/wiki/index.php?n=GameRef.PoliciesandPractices'><span>Policies and Practices</span></a></li>
            </ul>
         </li>
         <li><a href='/wiki/index.php?n=GameRef.CharacterCreation'><span>Character Creation</span></a>
         <ul>
         <li><a href='/wiki/index.php?n=GameRef.SanctioningGuide'><span>Sanctioning Checklist</span></a></li>
         <li><a href='/wiki/index.php?n=GameRef.BookPolicy'><span>Book Policy</span></a></li>
         </ul></li>
         <li><a href='/wiki/index.php?n=GameRef.ExperienceGuide'><span>Experience Guide</span></a></li>
         <li><a href='/wiki/index.php?n=GameRef.CrossoverPolicy'><span>Crossover Policy</span></a></li>
      </ul>
   </li>
   <li><span>Tools</span>
      <ul>
        <li><a href='/wiki/index.php?n=GameRef.Help'><span>Help</span></a>
            <ul>
				<li><a href='/wiki/index.php?n=GameRef.RequestSystemHelp'><span>Request System</span></a></li>
               <li><a href='/wiki/index.php?n=GameRef.ChatHelp'><span>Chat Interface</span></a></li>
            </ul>
         </li>
         <li><a href='/support.php'><span>Site Supporter</span></a></li>
      </ul>
   </li>
   <li><a href='/forum/index.php'><span>Forums</span></a></li>
   <li class='has-sub last'><span>Site Info</span>
      <ul>
	  <li><a href='/index.php?action=storytellers'><span>The Team</span></a></li>
         <li><a href='/wiki/index.php?n=GameRef.CodeOfConduct'><span>Code of Conduct</span></a>
            <ul>
               <li><a href='/wiki/index.php?n=GameRef.PersonalInformation'><span>Personal Information</span></a></li>
            </ul>
         </li>
         <li><a href='/wiki/index.php?n=GameRef.Disclaimer'><span>Disclaimer</span></a></li>
         <li><a href='/site_content.php?action=view&content_uid=links'><span>Links</span></a></li>
      </ul>
   </li>
</ul>
</div>
EOQ;
}