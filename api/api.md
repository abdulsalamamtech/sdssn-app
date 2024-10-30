# SDSSN APP

## Tables



### users
    - id
    - name (username)
    - first_name
    - last_name
    - email
    - password
    - security_question
    - answer

    - gender
    - dob
    - address
    - state
    - membership_status (free|trial|premium|gold|annual)

    - role (user|admin)
    - assigned_by (admin user_id)


### socials
    - id
    - user_id
    - github
    - linkedin
    - twitter
    - facebook
    - instagram


### assets (all file storage details)
    - id
    - title
    - path
    - url
    - type (image, audio)
    - file_size


### certificates (added by admin, belongs to user)
    - id
    - user_id (admin user_id)
    - asset_id (the certificate file)
    - course
    - details
    - belong_to (user_id)


### projects (map|discussion)
    - id
    - user_id
    - banner
    - title
    - description
    - tags
    - category (map|discussion)
    - status (public|private|draft)
    - approved_by (admin user_id)

    - views
    - likes
    - shares

    - deleted_at
    - deleted_by (admin user_id)


### comments
    - id
    - podcast_id
    - content
    
    - likes

### podcast (admin)
    - id
    - asset_id
    - banner
    - title
    - description
    - type (audio|video)

    - views
    - likes
    - shares


### messages
    - full_name
    - phone_number
    - email
    - message


## events (admin)
