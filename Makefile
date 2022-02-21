AWS_ENVS = AWS_ACCESS_KEY_ID AWS_SECRET_ACCESS_KEY AWS_STAGE AWS_REGION AWS_DEFAULT_REGION
DOCKER_REPO= $(shell AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID} AWS_SECRET_ACCESS_KEY=${AWS_SECRET_ACCESS_KEY} aws cloudformation --region ${AWS_REGION}  describe-stacks --stack-name $(CONTEXT)-${AWS_STAGE}-permanent | \
         	jq -r '.Stacks[0].Outputs[] | select(.OutputKey == "DockerImage").OutputValue')
CONTEXT = maybebaby
COMMIT_VERSION = $(shell git rev-parse --short HEAD)
ACCOUNT_ID= $(shell AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID} AWS_SECRET_ACCESS_KEY=${AWS_SECRET_ACCESS_KEY} aws sts get-caller-identity | jq -r ".Account")
CURR_DIR=$(shell pwd)
all:
	@echo "Available targets:"
	@echo "  * update			    - Publish image and update service"
	@echo "  * publish			    - Publish image"

.EXPORT_ALL_VARIABLES:
	CONTEXT = ${CONTEXT}

s3sync:
	aws s3 cp ./dist s3://${DOMAIN}/ --recursive --acl public-read

check-env-%:
	@ if [ "${${*}}" = "" ]; then \
			echo "Environment variable $* not set"; \
			exit 1; \
		fi


check-aws-env: $(addprefix check-env-,$(AWS_ENVS))

check-dependencies:
	@aws --version
	@jq --version
	@./node_modules/.bin/sls --version

prepublish: check-aws-env check-dependencies
	@echo "Checking if all required dependencies are present:"
	@aws --version
	@docker version

docker:
	@echo ">> Building docker image"
	docker build -t "$(DOCKER_REPO):$(COMMIT_VERSION)" -t "$(DOCKER_REPO):latest" -f Dockerfile .

docker-registry-login:
ifeq (,$(shell grep -s "${DOCKER_REPO}" "${HOME}/.docker/config.json"))
	@echo ">> Logging in to docker registry "
	@aws ecr get-login-password --region ${AWS_REGION} | docker login --username AWS --password-stdin $(ACCOUNT_ID).dkr.ecr.${AWS_REGION}.amazonaws.com
else
	@echo ">> Already logged in docker registry..."
endif


.PHONY: publish
publish: prepublish docker docker-registry-login
	@echo ">> Publishing docker image"
	@echo ">> $(DOCKER_REPO)/$(CONTEXT):$(COMMIT_VERSION)"
	@docker push "$(DOCKER_REPO):$(COMMIT_VERSION)"
	@docker push "$(DOCKER_REPO):latest"

update: publish
	aws ecs update-service --cluster $(CONTEXT)-${AWS_STAGE}-Cluster --service $(CONTEXT)-${AWS_STAGE}-Service --force-new-deployment
