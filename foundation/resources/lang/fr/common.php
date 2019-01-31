<?php

return [

    // Files
    'file_not_found' => 'Fichier non trouvé.',
    'file_uploaded_not_found' => 'Fichier manquant.',
    'file_not_valid' => 'Fichier non valide.',
    'file_extension_not_valid' => "Format de fichier non valide.",
    'file_max_size_error' => 'Ce fichier est trop gros.',
    'file_model_not_found' => 'Contexte du fichier inconnu.',

    // Status
    'draft_status_creation_error' => "
        Le seul statut autorisé est 'brouillon'.
    ",
    'archived_status_creation_error' => "
        Le statut 'archivé' n'est pas autorisé pour le moment.
    ",
    'draft_status_change_error' => "
        Le seul statut autorisé depuis un statut 'brouillon' est 'en service'.
    ",
    'active_status_change_error' => "
        Les statuts autorisés depuis un statut 'en service' sont 'maintenance' et 'archivé.
    ",
    'maintenance_status_change_error' => "
        Les statuts autorisés depuis un statut 'maintenance' sont 'en service' et 'archivé.
    ",
    'archived_status_change_error' => "
        Le statut 'archivé' ne peut être modifié.
    ",

];
