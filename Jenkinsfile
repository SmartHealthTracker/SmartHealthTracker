pipeline {
    agent any

    environment {
        APP_NAME = "smarthealth"
        DOCKER_IMAGE = "smarthealth:latest"
        NEXUS_URL = '192.168.33.10:8084'
        NEXUS_REPO = 'SH' 
        NEXUS_CREDENTIAL_ID = "nexus"
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'GestionEvent',
                    url: 'https://github.com/SmartHealthTracker/SmartHealthTracker.git'
            }
        }

        stage('Install dependencies') {
            steps {
                sh '''
                docker run --rm -v $PWD:/app -w /app composer install --no-interaction --prefer-dist
                '''
            }
        }

        stage('Generate APP_KEY') {
            steps {
                sh '''
                docker run --rm -v $PWD:/app -w /app composer bash -c "
                    if [ ! -f .env ]; then
                        cp .env.example .env
                    fi
                    php artisan key:generate
                "
                '''
            }
        }

        stage('Run Unit Tests') {
            steps {
                sh '''
                docker run --rm -v $PWD:/app -w /app composer bash -c "vendor/bin/phpunit"
                '''
            }
        }

        stage('SonarQube Analysis') {
            steps {
                script {
                    def scannerHome = tool 'jenkinsSonar'
                    withSonarQubeEnv('SonarQube') {
                        sh """
                        ${scannerHome}/bin/sonar-scanner \
                        -Dsonar.projectKey=${APP_NAME} \
                        -Dsonar.sources=. \
                        -Dsonar.exclusions=public/assets/plugins/**/*.js,vendor/**,node_modules/**,storage/**
                        """
                    }
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    sh "docker build -t ${DOCKER_IMAGE} ."
                }
            }
        }

        stage('Push to Nexus') {
            steps {
                script {
                    docker.withRegistry("http://${NEXUS_URL}", NEXUS_CREDENTIAL_ID) {
                        sh "docker tag ${DOCKER_IMAGE} ${NEXUS_URL}/${NEXUS_REPO}:latest"
                        sh "docker push ${NEXUS_URL}/${NEXUS_REPO}:latest"
                    }
                }
            }
        }

        stage('Deploy Application') {
            steps {
                script {
                    docker.withRegistry("http://${NEXUS_URL}", NEXUS_CREDENTIAL_ID) {
                        sh "docker pull ${NEXUS_URL}/${NEXUS_REPO}:latest"
                        sh "docker-compose down"
                        sh "docker-compose up -d --build"
                    }
                }
            }
        }
    } 

    post {
        success {
            echo 'Pipeline terminée avec succès !'
        }
        failure {
            echo 'Pipeline échouée.'
        }
    }
}
