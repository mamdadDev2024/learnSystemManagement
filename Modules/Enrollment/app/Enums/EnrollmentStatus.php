<?php

namespace Modules\Enrollment\Enums;

enum EnrollmentStatus
{
    case PENDING = "pending";
    case COMPLETED = "completed";
    case CANCELLED = "cancelled";
    case FAILED = "failed";
}
