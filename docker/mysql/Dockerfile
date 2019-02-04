# Base image
FROM mysql:8

# Copy custom conf file(s)
# .\conf being your relative host path from this Dockerfile
# \etc\mysql\conf.d\ being the VM path
COPY data/ /etc/mysql/conf.d/

COPY my.cnf /etc/mysql/conf.d/my.cnf

# Make the conf files not writeable so mysql will read them
# RUN chmod a-w /etc/mysql/conf.d/*