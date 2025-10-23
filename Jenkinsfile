pipeline {
    agent any

    environment {
        registryCredentials = "nexus"
        registry = "192.168.33.10:8083"
        NEXUS_VERSION = "nexus3"
        NEXUS_URL = '192.168.33.10:8083'
        NEXUS_PROTOCOL = "http"
        NEXUS_REPOSITORY = "pi"
        NEXUS_CREDENTIAL_ID = "nexus"
        APP_NAME = "smarthealth"
        DOCKER_IMAGE = "smarthealth:latest"
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'GestionEvent', url: 'https://github.com/SmartHealthTracker/SmartHealthTracker.git'
            }
    }


        stage('Install dependencies') {
            steps {
                script {
                    sh 'composer install --no-interaction --prefer-dist'
                }
            }
        }

        stage('Run Unit Tests') {
            steps {
                script {
                    sh 'vendor/bin/phpunit'
                }
            }
        }

        stage('SonarQube Analysis') {
            steps {
                script {
                    def scannerHome = tool 'jenkinsSonar'
                    withSonarQubeEnv('SonarQube') {
                        sh 'export SONAR_SCANNER_OPTS="-Xmx1024m"'
                        sh "${scannerHome}/bin/sonar-scanner -Dsonar.projectKey=${APP_NAME} -Dsonar.sources=."
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
                    docker.withRegistry("http://${registry}", registryCredentials) {
                        sh "docker tag ${DOCKER_IMAGE} ${registry}/smarthealth:latest"
                        sh "docker push ${registry}/smarthealth:latest"
                    }
                }
            }
        }

        stage('Deploy Application') {
            steps {
                script {
                    docker.withRegistry("http://${NEXUS_URL}", NEXUS_CREDENTIAL_ID) {
                        sh "docker pull ${NEXUS_URL}/smarthealth:latest"
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
