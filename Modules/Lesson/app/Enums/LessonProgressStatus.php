<?php

namespace Modules\Lesson\Enums;

enum LessonProgressStatus: int
{
    case NOT_STARTED = 0;
    case IN_PROGRESS = 1;
    case COMPLETED = 2;
}
