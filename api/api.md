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
    
    - phone_number
    - gender
    - dob
    - address
    - state
    - membership_status (free|trial|premium|gold|annual)

    - role (user|admin)
    - assigned_by (admin user_id)


    $ relationships (user)
        - has_one (social)
        - has_many (certificates)
        - has_many (projects)

    $ relationships (admin)
        - has_many (podcast)



### socials
    - id
    - user_id
    - github
    - linkedin
    - twitter
    - facebook
    - instagram


    $ relationships (user)
        - belongs_to (user)



### assets (all file storage details)
    - id
    - title
    - path
    - url
    - type (image|audio|video)
    - file_size 


    $ relationships
        - belongs_to (certificate)
        - belongs_to (projects)
        - belongs_to (podcast)



### certificates (added by admin, belongs to user)
    - id
    - added_by (admin user_id)
    - belong_to (user_id)
    - asset_id (the certificate file)
    - course
    - description


    $ relationships (user)
        - belongs_to (users) = belongs_to (user_id)

    $ relationships (admin)
        - belongs_to (users) = added_by (user_id)
    


### projects (map|discussion)
    - id
    - user_id
    - banner_id (on the assets table)
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

    $ relationships (user)
        - belongs_to (users)

    $ relationships (admin)
        - belongs_to (users) = approved_by (user_id)

    $ relationships 
        - has_many (comments)
    



### comments
    - id
    - user_id
    - project_id
    - content
    
    - likes


    $ relationships (user)
        - belongs_to (users) = belongs_to (user_id)

    $ relationships
        - belongs_to (project)
    


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

    $ relationships (user)
        - belongs_to (users)
    


= belongs_to (user_id)

    $ relationships (admin)
        - belongs_to (users) = added_by (user_id)
### messages
    - id
    - full_name
    - phone_number
    - email
    - message


## events (admin)
    - comming soon
