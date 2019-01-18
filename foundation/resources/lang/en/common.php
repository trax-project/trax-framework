<?php

return [

    // Files
    'file_not_found' => 'File not found.',
    'file_uploaded_not_found' => 'File is missing.',
    'file_not_valid' => 'File not valid.',
    'file_extension_not_valid' => 'File format not allowed.',
    'file_max_size_error' => 'This file is too big.',
    'file_model_not_found' => 'Unknown file context.',
    
    // Status
    'draft_status_creation_error' => "
        The only allowed status is 'draft'.
    ",
    'archived_status_creation_error' => "
        The 'archived' status is not allowed now.
    ",
    'draft_status_change_error' => "
        Allowed status from 'draft' is 'in service'.
    ",
    'active_status_change_error' => "
        Allowed status from 'in service' are 'maintenance' and 'archived'.
    ",
    'maintenance_status_change_error' => "
        Allowed status from 'maintenance' are 'in service' and 'archived'.
    ",
    'archived_status_change_error' => "
        The 'archived' status can't be changed.
    ",

];
