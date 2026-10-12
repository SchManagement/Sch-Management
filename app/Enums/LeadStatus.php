<?php

namespace App\Enums;

enum LeadStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Discussion = 'discussion';
    case Proposal = 'proposal';
    case Negotiation = 'negotiation';
    case Converted = 'converted';
    case Closed = 'closed';
}
