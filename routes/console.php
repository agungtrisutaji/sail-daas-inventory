<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:termination-create-command')->weekdays()->at('08:00');
// Schedule::command('app:extend-create-command')->weekdays()->at('08:00');
