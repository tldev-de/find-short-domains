# Find Short Domains
This project helps to find short .de domains that are still available for registration. These domains are useful, when you operate your own mail server and want to have a very short email address.

## .de domains
.de domains can consist of following characters: "a-z", "0-9", "-". They can't start or end with a "-", which means that the following regex can be used to validate domain candidates: `^[a-z0-9][1-z0-9\-]+[a-z0-9]\.de$`