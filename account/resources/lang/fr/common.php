<?php

return [

    // Home
    'custom_dashboard' => "Un tableau de bord devrait être créé pour votre projet.",

    // Auth
    'log_in' => 'Se connecter',
    'login' => 'Connexion',
    'logout' => 'Se déconnecter',
    'register' => "S'inscrire",
    'registration' => "Inscription",
    'change_password' => 'Changer le mot de passe',
    'reset_password' => 'Réinitialiser le mot de passe',
    'choose_password' => 'Choisissez un mot de passe',
    'forgot_password_q' => 'Mot de passe oublié ?',
    'password' => 'Mot de passe',
    'password_confirmation' => 'Confirmation du mot de passe',
    'password_reset' => 'Réinitialisation du mot de passe',
    'password_has_been_reset' => 'Mot de passe réinitialisé',
    'password_defined' => 'Mot de passe défini',
    'current_password' => 'Mot de passe actuel',
    'new_password' => 'Nouveau mot de passe',
    'new_password_confirmation' => 'Confirmation du mot de passe',
    'remember_me' => 'Se souvenir de moi',
    'sent_password_email_success' => '
        Veuillez vérifier vos emails. 
        Un lien de réinitialisation vient de vous être envoyé.',
    'sent_password_email_error' => "
        Il semble que vous ne soyez pas autorisé à réinitialiser votre mot de passe.
        Veuillez contacter l'administrateur de la plateforme.
    ",
    'invitation' => "Invitation",
    'invitation_email' => "Email d'invitation",
    'confirm_invitation_email_q' => "
        Si vous confirmez, un email d'invitation sera envoyé à l'utilisateur.
        Etes-vous sûr de vouloir faire cela ?
    ",
    'sent_invitation_email_success' => "L'invitation a bien été envoyée.",
    'sent_invitation_email_error' => "Désolé, l'invitation n'a pas pu être envoyée.",

    // Roles
    'role' => 'Rôle',
    'role_deletion' => "Suppression du rôle",
    'role_update' => "Modification du rôle",
    'roles' => 'Rôles',
    'roles_management' => 'Gestion des rôles',
    'role_definition' => "Définition d'un rôle",
    'new_role' => "Nouveau rôle",
    'permissions' => 'Permissions',
    'confirm_delete_role_q' => '
        Si vous confirmez, ce rôle sera définitivement supprimé !
        Etes-vous vraiment sûr de vouloir faire cela ?
    ',
    'role_deletion_exception' => "
        Ce rôle ne peut être supprimé car il est utilisé par un ou plusieurs comptes utilisateurs.
    ",
    
    // Entities
    'entities' => 'Entités',
    'entities_management' => 'Gestion des entités',
    'organization' => 'Organisation',
    'entity' => 'Entité',
    'new_entity' => 'Nouvelle entité',
    'new_organization' => 'Nouvelle organisation',
    'entity_deletion_exception' => "
        Cette entité ne peut être supprimée car elle est utilisée par un ou plusieurs comptes utilisateurs.
    ",

    // Users
    'users' => 'Utilisateurs',
    'user_account' => 'Compte utilisateur',
    'user_accounts' => 'Comptes utilisateurs',
    'users_management' => 'Gestion des utilisateurs',
    'user_update' => "Modification de l'utilisateur",
    'new_user' => "Nouvel utilisateur",
    'user_deletion' => "Suppression de l'utilisateur",
    'email' => 'Email',
    'firstname' => 'Prénom',
    'lastname' => 'Nom',
    'fullname' => 'Nom et prénom',
    'personal_data' => 'Informations personnelles',
    'account_type' => 'Type de compte',
    'no_organization' => "Pas d'organisation",
    'no_entity' => "Pas d'entité",
    'no_function' => "Aucune fonction",
    'no_role' => "Aucun rôle",
    'admin_account' => 'Super administrateur',
    'user_deletion_exception' => "
        Cet utilisateur ne peut être supprimé car il appartient à un groupe d'apprenants.
        Des données de suivi le concernant existent peut-être.
    ",
    'user_self_deletion_exception' => "
        Vous ne pouvez pas supprimer votre propre compte.
    ",

    // My profile
    'my_profile' => 'Mon profil',

    // Basic clients
    'basic_clients' => 'Clients Basic HTTP',
    'basic_clients_management' => 'Gestion des clients Basic HTTP',
    'client_update' => 'Modification du client ',
    'new_client' => 'Nouveau client',
    'client_deletion' => "Suppression du client",

    // Groups
    'group' => "Groupe",
    'groups' => "Groupes",
    'groups_management' => 'Gestion des groupes',
    'group_update' => 'Modification du groupe ',
    'new_group' => 'Nouveau groupe',
    'group_deletion' => "Suppression du groupe",
    'group_members' => "Membres du groupe",
    'confirm_delete_group_q' => '
        Si vous confirmez, ce groupe sera définitivement supprimé.,
        Les comptes utilisateurs seront toutefois préservés.
        Etes-vous vraiment sûr de vouloir faire cela ?',
    'group_deletion_exception' => "
        Ce groupe ne peut être supprimé car il est inscrit à une ou plusieurs formations.
    ",
    'cant_update_group_with_archived_registration' => "
        Ce groupe ne peut être supprimé car il est inscrit à une formation archivée.
    ",
    'cant_update_active_and_archived_group' => "
        Les groupes en service ou archivés ne peuvent pas être modifiés.
    ",
    'cant_update_archived_group' => "
        Les groupes archivés ne peuvent pas être modifiés.
    ",
    'cant_remove_user_from_group' => "
        L'utilisateur ne peut être retiré du groupe car il existe un suivi le concernant.
    ",

    // Agreements
    'user_agreement_title' => "Conditions d'utilisation",
    'user_agreement_empty' => "Les conditions d'utilisation ne sont pas encore définies.",
    'user_agreement_new_version' => "Nouvelle version (HTML)",
    'user_agreement_enter' => "Saisissez les conditions d'utilisation ici...",
    'user_agreement_publish' => 'Publier',
    'user_agreement_preview' => 'Prévisualiser',
    'user_agreement_approve' => "J'ai lu et je suis d'accord avec les conditions d'utilisation",
    'confirm_user_agreement_publish_q' =>
        "Si vous confirmez, tous les utilisateurs devront approuver les nouvelles conditions d'utilisation.
        Etes-vous vraiment sûr de vouloir faire cela ?",

    // Commons
    'accounts' => 'Comptes',
    'accounts_management' => 'Gestion des comptes',
    'active_account' => "Compte actif",
    'username' => "Nom d'utilisateur",
    'access' => "Accès",
    'rights' => 'Droits',
    'confirm_delete_account_q' => '
        Si vous confirmez, ce compte sera définitivement supprimé ! 
        Etes-vous vraiment sûr de vouloir faire cela ?',
    'not_allowed' => "Vous n'êtes pas autorisé à faire cela !",

    // Permissions
    'perm_users_management' => 'Admin: utilisateurs',
    'perm_groups_management' => 'Admin: groupes',
    'perm_roles_management' => 'Admin: rôles',
    'perm_entities_management' => 'Admin: entités',
    'perm_basic_clients_management' => 'Réglages: Clients Basic HTTP',
    'perm_agreement_write' => 'Réglages: rédaction des mentions légales',



];
