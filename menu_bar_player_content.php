<?php

/*$menu_bar .= <<<EOQ
<div id='navmenu'>
<ul>
   <li class='active'><a href='index.php'><span>Home</span></a></li>
   <li><span>The Setting</span>
      <ul>
         <li><a href='/wiki/index.php?n=City.City'><span>The City</span></a></li>
         <li><a href='/wiki/index.php?n=Changeling.Changeling'><span>Changeling</span></a>
         	<ul>
			   <li><a href='/wiki/index.php?n=Changeling.HouseRules'><span>Changeling House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Changeling.PlayerGuide'><span>Changeling Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Changeling'><span>Changeling Cast</span></a></li>
            </ul></li>
            <li><a href='/wiki/index.php?n=Geist.Geist'><span>Geist</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Geist.HouseRules'><span>Geist House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Geist.PlayerGuide'><span>Geist Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Geist'><span>Geist Cast</span></a></li></ul></li>
         <li><a href='/wiki/index.php?n=Mage.Mage'><span>Mage</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Mage.HouseRules'><span>Mage House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Mage.PlayerGuide'><span>Mage Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Mage'><span>Mage Cast</span></a></li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Mortal.Mortal'><span>Mortal</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Mortal.HouseRules'><span>Mortal House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Mortal.PlayerGuide'><span>Mortal Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Mortal'><span>Mortal Cast</span></a></li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Vampire.Vampire'><span>Vampire</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Vampire.HouseRules'><span>Vampire House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Vampire.PlayerGuide'><span>Vampire Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Vampire'><span>Vampire Cast</span></a></li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Werewolf.Werewolf'><span>Werewolf</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Werewolf.HouseRules'><span>Werewolf House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Werewolf.PlayerGuide'><span>Werewolf Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Werewolf'><span>Werewolf Cast</span></a></li>
            </ul></li>
            <li><a href=''><span>Crossover Sub-venues</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Whitefield.Whitefield'><span>Whitefield University</span></a>
			   <ul>
               <li><a href='/wiki/index.php?n=Whitefield.HouseRules'><span>Whitefield House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Whitefield.PlayerGuide'><span>Whitefield Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Whitefield'><span>Whitefield Cast</span></a></li>
            </ul>
			   </li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Players'><span>The Cast</span></a></li>
      </ul>
   </li>
   <li><span>Game Guide</span>
      <ul>
         <li><a href='#'><span>House Rules</span></a>
            <ul>
               <li><a href='/wiki/index.php?n=GameRef.HouseRules'><span>Errata and Clarifications</span></a></li>
               <li><a href='/wiki/index.php?n=GameRef.PoliciesandPractices'><span>Policies and Practices</span></a></li>
               <li><a href='/wiki/index.php?n=GameRef.CrossoverErrata'><span>Crossover Errata</span></a></li>
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
         <li><a href='chat.php'><span>Game/Chat Interface</span></a></li>
         <li><a href='/wiki/index.php?n=GameRef.Help'><span>Help</span></a>
            <ul>
               <li><a href='/wiki/index.php?n=GameRef.RequestSystemHelp'><span>Request System</span></a></li>
               <li><a href='/wiki/index.php?n=GameRef.ChatHelp'><span>Chat Interface</span></a></li>
            </ul>
         </li>
         <li><a href='support.php'><span>Site Supporter</span></a>
         <ul>
               <li><a href='http://wantonwicked.gamingsandbox.com/...?'><span>Manage Supporters</span></a></li></ul></li>
         <li class='has-sub last'><a href='#'><span>ST Tools</span></a>
         <ul>
               <li><a href='http://wantonwicked.gamingsandbox.com/...?'><span>ST Tools 1</span></a></li></ul>
         </li>
      </ul>
   </li>
   <li><a href='http://wantonwicked.gamingsandbox.com/forum/index.php'><span>Forums</span></a></li>
   <li class='has-sub last'><span>Site Info</span>
      <ul>
	  <li><a href='index.php?action=storytellers'><span>The Team</span></a></li>
         <li><a href='/wiki/index.php?n=GameRef.CodeOfConduct'><span>Code of Conduct</span></a>
            <ul>
               <li><a href='/wiki/index.php?n=GameRef.PersonalInformation'><span>Personal Information</span></a></li>
            </ul>
         </li>
         <li><a href='/wiki/index.php?n=GameRef.Disclaimer'><span>Disclaimer</span></a></li>
         <li><a href='site_content.php?action=view&content_uid=links'><span>Links</span></a></li>
      </ul>
   </li>
</ul>
</div>
EOQ;*/

$menu_bar .= <<<EOQ
<div id='navmenu'>
<ul>
   <li class='active'><a href='index.php'><span>Home</span></a></li>
   <li><span>The Setting</span>
      <ul>
         <li><a href='/wiki/index.php?n=City.City'><span>The City</span></a></li>
         <li><a href='/wiki/index.php?n=Changeling.Changeling'><span>Changeling</span></a>
         	<ul>
			   <li><a href='/wiki/index.php?n=Changeling.HouseRules'><span>Changeling House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Changeling.PlayerGuide'><span>Changeling Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Changeling'><span>Changeling Cast</span></a></li>
            </ul></li>
            <li><a href='/wiki/index.php?n=Geist.Geist'><span>Geist</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Geist.HouseRules'><span>Geist House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Geist.PlayerGuide'><span>Geist Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Geist'><span>Geist Cast</span></a></li></ul></li>
         <li><a href='/wiki/index.php?n=Mage.Mage'><span>Mage</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Mage.HouseRules'><span>Mage House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Mage.PlayerGuide'><span>Mage Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Mage'><span>Mage Cast</span></a></li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Mortal.Mortal'><span>Mortal</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Mortal.HouseRules'><span>Mortal House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Mortal.PlayerGuide'><span>Mortal Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Mortal'><span>Mortal Cast</span></a></li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Vampire.Vampire'><span>Vampire</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Vampire.HouseRules'><span>Vampire House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Vampire.PlayerGuide'><span>Vampire Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Vampire'><span>Vampire Cast</span></a></li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Werewolf.Werewolf'><span>Werewolf</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Werewolf.HouseRules'><span>Werewolf House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Werewolf.PlayerGuide'><span>Werewolf Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Werewolf'><span>Werewolf Cast</span></a></li>
            </ul></li>
            <li><a href=''><span>Crossover Sub-venues</span></a>
         	<ul>
               <li><a href='/wiki/index.php?n=Whitefield.Whitefield'><span>Whitefield University</span></a>
			   <ul>
               <li><a href='/wiki/index.php?n=Whitefield.HouseRules'><span>Whitefield House Rules</span></a></li>
			   <li><a href='/wiki/index.php?n=Whitefield.PlayerGuide'><span>Whitefield Player Guide</span></a></li>
			   <li><a href='/wiki/index.php?n=Players.Whitefield'><span>Whitefield Cast</span></a></li>
            </ul>
			   </li>
            </ul></li>
         <li><a href='/wiki/index.php?n=Players'><span>The Cast</span></a></li>
      </ul>
   </li>
   <li><span>Game Guide</span>
      <ul>
         <li><a href='#'><span>House Rules</span></a>
            <ul>
               <li><a href='/wiki/index.php?n=GameRef.HouseRules'><span>Errata and Clarifications</span></a></li>
               <li><a href='/wiki/index.php?n=GameRef.PoliciesandPractices'><span>Policies and Practices</span></a></li>
               <li><a href='/wiki/index.php?n=GameRef.CrossoverErrata'><span>Crossover Errata</span></a></li>
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
         <li><a href='chat.php'><span>Game/Chat Interface</span></a></li>
         <li><a href='/wiki/index.php?n=GameRef.Help'><span>Help</span></a>
            <ul>
               <li><a href='/wiki/index.php?n=GameRef.RequestSystemHelp'><span>Request System</span></a></li>
               <li><a href='/wiki/index.php?n=GameRef.ChatHelp'><span>Chat Interface</span></a></li>
            </ul>
         </li>
         <li><a href='support.php'><span>Site Supporter</span></a>
         <ul>
               <li><a href='http://wantonwicked.gamingsandbox.com/...?'><span>Manage Supporters</span></a></li></ul></li>
         <li class='has-sub last'><a href='#'><span>ST Tools</span></a>
         <ul>
               <li><a href='http://wantonwicked.gamingsandbox.com/...?'><span>ST Tools 1</span></a></li></ul>
         </li>
      </ul>
   </li>
   <li><a href='http://wantonwicked.gamingsandbox.com/forum/index.php'><span>Forums</span></a></li>
   <li class='has-sub last'><span>Site Info</span>
      <ul>
	  <li><a href='index.php?action=storytellers'><span>The Team</span></a></li>
         <li><a href='/wiki/index.php?n=GameRef.CodeOfConduct'><span>Code of Conduct</span></a>
            <ul>
               <li><a href='/wiki/index.php?n=GameRef.PersonalInformation'><span>Personal Information</span></a></li>
            </ul>
         </li>
         <li><a href='/wiki/index.php?n=GameRef.Disclaimer'><span>Disclaimer</span></a></li>
         <li><a href='site_content.php?action=view&content_uid=links'><span>Links</span></a></li>
      </ul>
   </li>
</ul>
</div>
EOQ;
