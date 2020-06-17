<?php

/**
 * Copyright (c) 2020 PJZ9n.
 *
 * This file is part of use-pmforms.
 *
 * use-pmforms is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * use-pmforms is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with use-pmforms. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace PJZ9n\UsePmforms;

use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Dropdown;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\element\Slider;
use dktapps\pmforms\element\StepSlider;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase
{
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()) {
            case "pmforms":
                if (!$sender instanceof Player) {
                    $sender->sendMessage(TextFormat::RED . "このコマンドはゲーム内から実行してください。");
                    return true;
                }
                $element1 = new Dropdown("rate", "サーバーの評価", ["とても良い", "良い", "悪い", "とても悪い"], 0);
                $element2 = new StepSlider("sns", "好きなSNS", ["Instagram", "LINE", "Twitter"], 2);
                $sender->sendForm(new CustomForm(
                    "テストフォーム",
                    [
                        new Input("name", "名前", "Steve", "Alex"),
                        new Input("food", "好きな食べ物", "Apple", "Banana"),
                        $element1,
                        new Slider("age", "年齢", 0, 250, 1, (float)50),//第6引数がfloatを要求するため
                        $element2,
                    ],
                    function (Player $player, CustomFormResponse $response) use ($element1, $element2): void {
                        var_dump($response->getAll());
                        $text = "";
                        $text .= "名前: {$response->getString("name")}\n";
                        $text .= "好きな食べ物: {$response->getString("food")}\n";
                        $text .= "サーバーの評価: {$element1->getOption($response->getInt("rate"))}\n";
                        $text .= "年齢: {$response->getInt("age")}\n";
                        $text .= "好きなSNS: {$element2->getOption($response->getInt("sns"))}\n";
                        $player->sendMessage($text);
                    }
                ));
                return true;
        }
        return false;
    }
}
