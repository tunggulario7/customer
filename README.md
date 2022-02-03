# Customer Data
## Install the Application

Run this command from the directory in which you want to install your Customer Application. You will require PHP 7.3 or newer.

```bash
composer install
```

After finish running composer, you can run docker using this command :
```bash
docker-compose up -d
```

## Url for POST Customer Data :
http://localhost:8080/customer

### Json Body Example for POST Customer Data :

{
"name": "Lorem Ipsum",

"ktp": 123456789,

"loanAmount": 5000,

"loanPeriod": 12,

"loanPurpose": "vacation",

"dateOfBirth": "2000-01-22",

"sex": "M"
}


## Url for GET Customer Data :
http://localhost:8080/customer

### Json Response Example for GET Customer Data :

[
{
"name": "Lorem Ipsum",

"ktp": "123456789",

"loanAmount": "5000",

"loanPeriod": "12",

"loanPurpose": "vacation",

"dateOfBirth": "2000-01-22",

"sex": "Male"
},

{
"name": "Dolor Ipsum",

"ktp": "987654321",

"loanAmount": "7000",

"loanPeriod": "6",

"loanPurpose": "renovation",

"dateOfBirth": "1999-01-22",

"sex": "Male"
}
]


##Setup Migration Database

### Create Migration Table

```bash
php vendor/bin/phoenix create <migration>
```

### Running Migrate Table
```bash
php vendor/bin/phoenix migrate
```
### Rollback Migrate Table
```bash
php vendor/bin/phoenix rollback
```