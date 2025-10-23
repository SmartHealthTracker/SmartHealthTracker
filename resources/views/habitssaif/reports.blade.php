<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Performance - {{ $habit->title }}</title>
    <style>
        /* === RESET === */
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        /* === BASE === */
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            background: #ffffff; 
            color: #2c3e50; 
            line-height: 1.4;
            padding: 20px;
            font-size: 12px;
        }
        
        .container { 
            max-width: 1200px; 
            margin: 0 auto;
            background: white;
        }

        /* === HEADER === */
        .header { 
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); 
            color: white; 
            padding: 25px 30px; 
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            font-size: 13px;
            opacity: 0.9;
        }

        /* === 6 CARTES STATISTIQUES === */
        .stats-grid-six {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 12px;
            margin-bottom: 25px;
        }
        
        .stat-card-six {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 20px 15px;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }
        
        .stat-value-six {
            font-size: 20px;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 5px;
            font-family: 'DejaVu Sans', monospace;
        }
        
        .stat-label-six {
            font-size: 10px;
            color: #7f8c8d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* === SECTIONS === */
        .section { 
            margin-bottom: 25px; 
        }
        
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #3498db;
        }

        /* === TABLEAUX AVEC BORDURES === */
        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            border: 2px solid #e9ecef;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        
        .data-table th {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            color: white;
            padding: 12px 10px;
            text-align: left;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-right: 1px solid #4a6278;
        }
        
        .data-table th:last-child {
            border-right: none;
        }
        
        .data-table td {
            padding: 10px 10px;
            border-bottom: 1px solid #e9ecef;
            border-right: 1px solid #e9ecef;
            font-size: 11px;
        }
        
        .data-table td:last-child {
            border-right: none;
        }
        
        .data-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }

        /* === RECOMMANDATIONS === */
        .recos-container {
            background: linear-gradient(135deg, #e8f6f3 0%, #d1f2eb 100%);
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #1abc9c;
            margin-bottom: 20px;
        }
        
        .recos-title {
            font-size: 14px;
            font-weight: 700;
            color: #16a085;
            margin-bottom: 15px;
        }
        
        .recos-grid {
            display: grid;
            gap: 12px;
        }
        
        .reco-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #1abc9c;
            border: 1px solid #d1f2eb;
        }
        
        .reco-title {
            font-size: 12px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .reco-text {
            color: #5d6d7e;
            line-height: 1.4;
            font-size: 11px;
        }

        /* === ANALYSE === */
        .analysis-container {
            background: linear-gradient(135deg, #fef9e7 0%, #fcf3cf 100%);
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #f39c12;
            margin-bottom: 20px;
        }
        
        .analysis-title {
            font-size: 14px;
            font-weight: 700;
            color: #d35400;
            margin-bottom: 12px;
        }
        
        .analysis-content {
            color: #2c3e50;
            line-height: 1.5;
            font-size: 11px;
            white-space: pre-line;
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #f7dc6f;
        }

        /* === SYNTHESE === */
        .synthese-container {
            background: linear-gradient(135deg, #e8f4fd 0%, #d6eaf8 100%);
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #3498db;
        }
        
        .synthese-title {
            font-size: 14px;
            font-weight: 700;
            color: #2980b9;
            margin-bottom: 15px;
        }
        
        .synthese-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
        
        .synthese-item {
            background: white;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #d6eaf8;
            text-align: center;
        }
        
        .synthese-label {
            font-size: 10px;
            font-weight: 600;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .synthese-value {
            font-size: 12px;
            font-weight: 700;
            color: #2980b9;
        }

        /* === BADGES === */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .badge-success {
            background: #d5f4e6;
            color: #27ae60;
            border: 1px solid #27ae60;
        }
        
        .badge-warning {
            background: #fdebd0;
            color: #f39c12;
            border: 1px solid #f39c12;
        }
        
        .badge-danger {
            background: #fadbd8;
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        /* === FOOTER === */
        .footer {
            text-align: center;
            margin-top: 25px;
            padding: 15px;
            background: #ecf0f1;
            border-radius: 6px;
            color: #7f8c8d;
            font-size: 10px;
            border: 1px solid #bdc3c7;
        }

        /* === RESPONSIVE === */
        @media (max-width: 968px) {
            .stats-grid-six {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .synthese-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 600px) {
            .stats-grid-six {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .synthese-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <h1>📊 RAPPORT DE PERFORMANCE</h1>
            <div class="subtitle">{{ $habit->title }} • {{ now()->format('d/m/Y à H:i') }}</div>
        </div>

        <!-- 6 CARTES STATISTIQUES -->
        <div class="stats-grid-six">
            <div class="stat-card-six">
                <div class="stat-value-six">{{ $totalSessions }}</div>
                <div class="stat-label-six">SESSIONS TOTAL</div>
            </div>
            <div class="stat-card-six">
                <div class="stat-value-six">{{ $totalDays }}</div>
                <div class="stat-label-six">JOURS ACTIFS</div>
            </div>
            <div class="stat-card-six">
                <div class="stat-value-six">{{ $progress }}%</div>
                <div class="stat-label-six">PROGRESSION</div>
            </div>
            <div class="stat-card-six">
                <div class="stat-value-six">{{ $averagePerDay }}</div>
                <div class="stat-label-six">MOYENNE/JOUR</div>
            </div>
            <div class="stat-card-six">
                <div class="stat-value-six">{{ $averageValue }}</div>
                <div class="stat-label-six">MOYENNE/SESSION</div>
            </div>
            <div class="stat-card-six">
                <div class="stat-value-six">{{ $bestValue }}</div>
                <div class="stat-label-six">MEILLEURE SESSION</div>
            </div>
        </div>

        <!-- ACTIVITÉ QUOTIDIENNE -->
        <div class="section">
            <div class="section-title">📅 ACTIVITÉ PAR JOUR</div>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Date</th>
                            <th style="width: 20%; text-align: center;">Sessions</th>
                            <th style="width: 20%; text-align: center;">Calories</th>
                            <th style="width: 20%; text-align: center;">Moyenne</th>
                            <th style="width: 20%; text-align: center;">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyDetails as $detail)
                        <tr>
                            <td style="font-weight: 700;">{{ $detail['date'] }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ $detail['habits_count'] }}</td>
                            <td style="text-align: center;">{{ $detail['calories'] }} kCal</td>
                            <td style="text-align: center;">{{ $detail['average_value'] }}</td>
                            <td style="text-align: center;">
                                @if($detail['habits_count'] > 0)
                                    <span class="badge badge-success">ACTIF</span>
                                @else
                                    <span class="badge badge-danger">INACTIF</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- HISTORIQUE COMPLET -->
        <div class="section">
            <div class="section-title">📋 HISTORIQUE DES SESSIONS</div>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Date & Heure</th>
                            <th style="width: 15%; text-align: center;">Valeur</th>
                            <th style="width: 20%; text-align: center;">Calories</th>
                            <th style="width: 20%; text-align: center;">Jour</th>
                            <th style="width: 20%; text-align: center;">Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td style="font-weight: 600;">{{ \Carbon\Carbon::parse($log->logged_at)->format('d/m H:i') }}</td>
                            <td style="text-align: center; font-weight: 800;">{{ $log->value }}</td>
                            <td style="text-align: center; font-weight: 600;">{{ $log->value }} kCal</td>
                            <td style="text-align: center;">{{ \Carbon\Carbon::parse($log->logged_at)->translatedFormat('D') }}</td>
                            <td style="text-align: center;">
                                @if($log->value >= 20)
                                    <span class="badge badge-success">EXCELLENT</span>
                                @elseif($log->value >= 15)
                                    <span class="badge badge-success">BON</span>
                                @elseif($log->value >= 10)
                                    <span class="badge badge-warning">MOYEN</span>
                                @else
                                    <span class="badge badge-danger">FAIBLE</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RECOMMANDATIONS -->
        <div class="section">
            <div class="section-title">💡 RECOMMANDATIONS</div>
            
            <div class="recos-container">
                <div class="recos-title">🎯 STRATÉGIES D'AMÉLIORATION</div>
                
                <div class="recos-grid">
                    @foreach($recommendations as $reco)
                    <div class="reco-item">
                        <div class="reco-title">{{ $reco['title'] }}</div>
                        <div class="reco-text">{{ $reco['description'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ANALYSE DÉTAILLÉE -->
        <div class="section">
            <div class="section-title">🔍 ANALYSE APPROFONDIE</div>
            
            <div class="analysis-container">
                <div class="analysis-title">📊 RAPPORT DE PERFORMANCE</div>
                <div class="analysis-content">
                    {{ $detailedAnalysis }}
                </div>
            </div>
        </div>

        <!-- SYNTHÈSE -->
        <div class="section">
            <div class="section-title">📈 SYNTHÈSE</div>
            
            <div class="synthese-container">
                <div class="synthese-title">📋 BILAN COMPLET</div>
                
                <div class="synthese-grid">
                    <div class="synthese-item">
                        <div class="synthese-label">Période analysée</div>
                        <div class="synthese-value">{{ $periodStart }} - {{ $periodEnd }}</div>
                    </div>
                    <div class="synthese-item">
                        <div class="synthese-label">Calories totales</div>
                        <div class="synthese-value">{{ $totalCalories }} kCal</div>
                    </div>
                    <div class="synthese-item">
                        <div class="synthese-label">Régularité</div>
                        <div class="synthese-value">{{ $consistency }}%</div>
                    </div>
                    <div class="synthese-item">
                        <div class="synthese-label">Tendance</div>
                        <div class="synthese-value">{{ $trend }}</div>
                    </div>
                    <div class="synthese-item">
                        <div class="synthese-label">Objectif</div>
                        <div class="synthese-value">{{ $progress }}%</div>
                    </div>
                    <div class="synthese-item">
                        <div class="synthese-label">Sessions/jour</div>
                        <div class="synthese-value">{{ $averagePerDay }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            Rapport généré automatiquement • {{ $habit->user->name ?? 'Utilisateur' }} • 
            {{ $totalSessions }} sessions analysées • {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>