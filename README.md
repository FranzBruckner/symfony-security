Security Component - Core
=========================

# Canary deployment
## Enabling the possibility that SF 3.* and SF 4.* applications can coexist
The changes in this branch enable canary deployment for applications that have been upgraded from Symfony 3 to Symfony 4.  
The upgraded version (Symfony 4) can run on a canary server, while the same application with Symfony 3 runs on the existing productive server.

The challenge is that the user logs in on one system and stays logged in when he switches to the other server.

This branch adapts the structure of the serialized session for the Symfony 3 application to the structure used by Symfony 4.
