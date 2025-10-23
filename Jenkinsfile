pipeline {
    agent any

    environment {
        APP_NAME = "smarthealth"
        DOCKER_IMAGE = "smarthealth:latest"
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'GestionEvent', url: 'https://github.com/SmartHealthTracker/SmartHealthTracker.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    sh 'docker build -t $DOCKER_IMAGE .'
                }
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    // Exemple : exécuter tests PHPUnit
                    sh 'docker run --rm $DOCKER_IMAGE vendor/bin/phpunit'
                }
            }
        }

        stage('Push to Registry') {
            steps {
                script {
                    // Si tu as un registry privé ou Docker Hub
                    // sh 'docker tag $DOCKER_IMAGE your-dockerhub-user/$DOCKER_IMAGE'
                    // sh 'docker push your-dockerhub-user/$DOCKER_IMAGE'
                    echo "Push to registry skipped for now"
                }
            }
        }

        stage('Deploy') {
            steps {
                script {
                    // Déploiement simple via docker-compose
                    sh 'docker-compose down'
                    sh 'docker-compose up -d --build'
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
