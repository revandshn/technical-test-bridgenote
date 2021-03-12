## About the API

Here some end point :


**Authentication**
```
POST Sign In : localhost:8000/api/v1/signin
POST Sign Up : localhost:8000/api/v1/signup
GET Sign Out : localhost:8000/api/v1/signout
```


**Profile**
```
GET Get My Profile : localhost:8000/api/v1/get-profile
POST Update Profile : localhost:8000/api/v1/update-profile

--Administrator
GET Get All Users Profile : localhost:8000/api/v1/user-profile
GET Get User Profile : localhost:8000/api/v1/get-profile/:user_id
DELETE Delete User : localhost:8000/api/v1/del-profile/:user_id
POST Restore User : localhost:8000/api/v1/restore-profile
```

**User Detail**
```
--Administrator
POST Create User Detail : localhost:8000/api/v1/user-detail/:user_id
GET Get User Detail : localhost:8000/api/v1/user-detail/:user_id
PUT Update User Detail : localhost:8000/api/v1/user-detail/:user_id
DELETE Delete User Detail : localhost:8000/api/v1/user-detail/:user_id
```
