<?php

namespace ChampOfGames\GroupsUI;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener
{
    public bool $check = true;
    public function glist(): array
    {

        $groups = array();
        $api = $this->getServer()->getPluginManager()->getPlugin('PurePerms');
        foreach ($api->getGroups() as $group) {

            $groups[] = $group->getName();
            
        }
        return $groups;
    }
    public function PName(): array
    {
        $list = array();
        foreach (Server::getInstance()->getOnlinePlayers() as $players) {
            $list[] = $players->getName();
        
        }
        return $list;
    }


    public function openGroupUI($player): void
    {
        $form = new SimpleForm(function (Player $player, $data) {

            $result = $data;
            if ($result === null) {
                return;
            }
            switch ($result) {
                case 0:
                    $this->openRCUI($player);
                    break;
                case 1:
                    $this->openNFUI($player);
                    break;
                case 2:
                    $this->openPermsUI($player);
                    break;
                case 3:
                    $this->openARPUI($player);
                    break;
            }
        });

        $form->setTitle("GroupsUI");
        $form->setContent("Choose what you want to do.");
        $form->addButton("§2Create §ror §4delete §ra group");
        $form->addButton("§9Set the nametag or the format of a group");
        $form->addButton("§2Add §ror §4remove §ra permission for a group");
        $form->addButton("§2Change §rthe group of a player.");
        $form->addButton("Close");

        $player->sendForm($form);
    }

    public function openRCUI($player): void
    {
        $form = new SimpleForm(function (Player $player, $data) {
            $result = $data;
            if ($result === null) {
                return;
            }
            switch ($result) {
                case 0:
                    $this->openCreateUI($player);
                    break;
                case 1:
                    $this->openRemoveUI($player);
                    break;
                case 2:
                    $this->openGroupUI($player);
                    break;
            }
        });

        $form->setTitle("GroupsUI");
        $form->addButton("Create a group.");
        $form->addButton("Remove a group");
        $form->addButton("Go back");

        $player->sendForm($form);
    }

    public function openCreateUI($player): void
    {
        $form = new CustomForm(function (Player $player, $data) {

            if (isset($data[0])) {
                $this->getServer()->getCommandMap()->dispatch($player, "addgroup $data[0]");
            }
            $this->openGroupUI($player);
        });

        $form->setTitle("GroupsUI");
        $form->addInput("Create a group.");
        $player->sendForm($form);
    }
    public function openRemoveUI($player): void
    {
        $form = new CustomForm(function (Player $player, $data) {

            if (isset($data[0])) {
                $this->getServer()->getCommandMap()->dispatch($player, "rmgroup $data[0]");
            }
            $this->openGroupUI($player);
        });

        $form->setTitle("GroupsUI");
        $form->addInput("Remove a group.");

        $player->sendForm($form);
    }


    public function openNFUI($player): void
    {
        $form = new SimpleForm(function (Player $player, $data) {
            $result = $data;
            if ($result === null) {
                return;
            }
            switch ($result) {
                case 0:
                    $this->openNametagUI($player);
                    break;
                case 1:
                    $this->openFormatUI($player);
                    break;
                case 2:
                    $this->openGroupUI($player);
                    break;
            }
        });

        $form->setTitle("GroupsUI");
        $form->addButton("Edit the nametag of a group.");
        $form->addButton("Edit the format of a group.");
        $form->addButton("Go back");

        $player->sendForm($form);
    }
    public function openNametagUI($player): void
    {
        $form = new CustomForm(function (Player $player, $data) {
            if (isset($data[1])) {
                $groups = $this->glist();
                $this->getServer()->getCommandMap()->dispatch($player, "setnametag " . $groups[$data[1]] . " global " . $data[2]);
            }
            $this->openGroupUI($player);
        });

        $form->setTitle("GroupsUI");
        $form->addLabel("Available PureChat tags: {display_name}, {msg}, {fac_name}, {fac_rank}, {prefix}, {suffix}, {world}.");
        $form->addDropdown("Available Groups.", $this->glist());
        $form->addInput("Edit the nametag.");

        $player->sendForm($form);
    }
    public function openFormatUI($player): void
    {
        $form = new CustomForm(function (Player $player, $data) {

            if (isset($data[1])) {
                $groups = $this->glist();
                $this->getServer()->getCommandMap()->dispatch($player, "setformat " . $groups[$data[1]] . " global " . $data[2]);
            }
            $this->openGroupUI($player);
        });

        $form->setTitle("GroupsUI");
        $form->addLabel("Available PureChat tags: {display_name}, {msg}, {fac_name}, {fac_rank}, {prefix}, {suffix}, {world}.");
        $form->addDropdown("Available Groups.", $this->glist());
        $form->addInput("Edit the format of a group.");

        $player->sendForm($form);
    }

    public function openPermsUI($player): void
    {
        $form = new SimpleForm(function (Player $player, $data) {
            $result = $data;
            if ($result === null) {
                return;
            }
            switch ($result) {
                case 0:
                    $this->openPaddUI($player);
                    break;
                case 1:
                    $this->openPremUI($player);
                    break;
                case 2:
                    $this->openGroupUI($player);
                    break;
            }
        });

        $form->setTitle("GroupsUI");
        $form->addButton("§2Add §ra permission to a group.");
        $form->addButton("§4Remove §ra permission from a group.");
        $form->addButton("Go back");

        $player->sendForm($form);
    }
    public function openPaddUI($player): void
    {
        $form = new CustomForm(function (Player $player, $data) {

            if (isset($data[0])) {
                $groups = $this->glist();
                $this->getServer()->getCommandMap()->dispatch($player, "setgperm " . $groups[$data[0]] . " " . $data[1]);
                $this->openGroupUI($player);
            } else if (empty($data)) {
                $this->openGroupUI($player);
            }
        });

        $form->setTitle("GroupsUI");
        $form->addDropdown("Available Groups.", $this->glist());
        $form->addInput("Enter the permission that you want to add.");

        $player->sendForm($form);
    }

    public function openPremUI($player): void
    {
        $form = new CustomForm(function (Player $player, $data) {
            if ($data === null) return;
            if (isset($data[1])) {
                $groups = $this->glist();

                $this->getServer()->getCommandMap()->dispatch($player, "unsetgperm " . $groups[$data[1]] . " " . $data[2]);
            }
            $this->openGroupUI($player);
        });

        $form->setTitle("GroupsUI");
        $form->addLabel("");
        $form->addDropdown("Available Groups.", $this->glist());
        $form->addInput("Enter the permission that you want to remove.");

        $player->sendForm($form);
    }
    public function openARPUI($player): void
    {
        $form = new SimpleForm(function (Player $player, $data) {
            $result = $data;
            if ($result === null) {
                return;
            }
            switch ($result) {
                case 0:
                    $this->openPladdUI($player);
                    break;
                case 1:
                    $this->openGroupUI($player);
                    break;
            }
        });

        $form->setTitle("GroupsUI");
        $form->addButton("§2Change §rthe group of a player.");
        $form->addButton("Go back");

        $player->sendForm($form);
    }
    public function openPLaddUI($player): void
    {
        $form = new CustomForm(function (Player $player, $data) {

            if (isset($data[1])) {
                $groups = $this->glist();
                $ps = $this->PName();

                $this->getServer()->getCommandMap()->dispatch($player, "setgroup " . $ps[$data[1]] . " " . $groups[$data[2]]);
            }
            $this->openGroupUI($player);
        });

        $form->setTitle("GroupsUI");
        $form->addLabel("");
        $form->addDropdown("Online player.", $this->PName());
        $form->addDropdown("Available Groups.", $this->glist());

        $player->sendForm($form);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command->getName() === "groupsui") {
            if ($sender instanceof Player) {
                $this->openGroupUI($sender);
            }
        }
        return true;
    }
}
