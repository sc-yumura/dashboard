<?php

namespace App;

enum UserStatus
{
    case Active; // アクティブ
    case Canceled; // 退会済み
    case Frozen; // 凍結
}
