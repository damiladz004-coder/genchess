<?php

return [
    'standard_template' => [
        'lesson_header' => [
            'Module Number',
            'Topic Title',
            'Duration',
            'Level (Beginner / Advanced)',
            'Objectives (3-5 clear outcomes)',
        ],
        'video_structure' => [
            'Introduction (Hook)',
            'Core Explanation',
            'Demonstration',
            'Guided Practice',
            'Teaching Method (How to Teach It)',
            'Recap',
        ],
        'lesson_notes' => [
            'Definitions',
            'Diagram explanations',
            'Key principles',
            'Common mistakes',
            'Teaching tips',
        ],
        'quiz_structure' => [
            '5-10 MCQs',
            '2 True/False',
            '1 Practical scenario question',
            'Pass mark: 70%',
        ],
        'practical_assignment' => [
            'Board demonstration',
            'Puzzle solving',
            'Teaching simulation',
            'Written reflection (for advanced)',
        ],
    ],
    'modules' => [
        [
            'title' => 'Module 1 - Introduction to Chess',
            'goal' => 'Build absolute foundation.',
            'topics' => [
                [
                    'title' => 'Understanding the Chessboard',
                    'video' => [
                        'Board orientation',
                        '64 squares',
                        'Files, ranks, diagonals',
                        'Algebraic notation intro',
                    ],
                    'quiz' => [
                        'Coordinates',
                        'Orientation rule',
                        'Identifying square color',
                    ],
                    'assessment' => [
                        'Identify 8 random squares correctly',
                        'Set board correctly',
                    ],
                ],
                [
                    'title' => 'Setting Up the Chessboard',
                    'video' => [
                        'Pawn placement',
                        'Major pieces',
                        'Queen on her color rule',
                        'Speed setup challenge',
                    ],
                    'quiz' => [
                        'Starting squares',
                        'Queen color rule',
                        'Setup timing',
                    ],
                    'assessment' => [
                        'Full board setup under 3 minutes',
                    ],
                ],
                [
                    'title' => 'Chess Pieces (Moves, Values, Symbols)',
                    'video' => [
                        'Piece-by-piece breakdown',
                        'Movement',
                        'Value',
                        'Capture examples',
                    ],
                    'quiz' => [
                        'Piece value',
                        'Legal moves',
                        'Symbol recognition',
                    ],
                    'assessment' => [
                        'Mini-game demonstration',
                    ],
                ],
                [
                    'title' => 'Check, Checkmate, Stalemate',
                    'video' => [
                        'What is check?',
                        'Escaping check (3 ways)',
                        'Checkmate examples',
                        'Stalemate traps',
                    ],
                    'quiz' => [
                        'Identify checkmate',
                        'Identify stalemate',
                        'Escape check scenario',
                    ],
                    'assessment' => [
                        'Demonstrate King + Queen mate',
                    ],
                ],
            ],
        ],
        [
            'title' => 'Module 2 - Rules & Special Moves',
            'topics' => [
                [
                    'title' => 'Castling, En Passant, Promotion',
                    'video' => [
                        'Castling rules',
                        'Conditions checklist',
                        'En passant explanation',
                        'Underpromotion scenario',
                    ],
                    'quiz' => [
                        'Legal/illegal castling',
                        'En passant timing',
                        'Promotion options',
                    ],
                    'assessment' => [
                        'Demonstrate all 3 special moves correctly',
                    ],
                ],
                [
                    'title' => 'Notation (Recording Games)',
                    'video' => [
                        'Coordinates review',
                        'Symbols',
                        'Writing moves',
                        'Reading a sample game',
                    ],
                    'quiz' => [
                        'Translate move to notation',
                        'Recognize notation symbols',
                    ],
                    'assessment' => [
                        'Record 15-move game correctly',
                    ],
                ],
                [
                    'title' => 'Chess Etiquette & Rules',
                    'video' => [
                        'Touch-move',
                        'Clock usage',
                        'Tournament behavior',
                        'Classroom enforcement',
                    ],
                    'quiz' => [
                        'Illegal move handling',
                        'Touch-move rule',
                    ],
                    'assessment' => [
                        'Simulated tournament scenario',
                    ],
                ],
            ],
        ],
        [
            'title' => 'Module 3 - Basic Strategy & Tactics',
            'topics' => [
                [
                    'title' => 'Hanging Pieces & Trades',
                    'video' => [
                        'Hanging piece definition',
                        'Good vs bad capture',
                        'Trade examples',
                        'Traps',
                    ],
                    'quiz' => [
                        'Identify hanging piece',
                        'Evaluate trade',
                    ],
                    'assessment' => [
                        'Spot 5 hanging pieces in diagrams',
                    ],
                ],
                [
                    'title' => 'Game Phases & Move Priorities',
                    'video' => [
                        'Opening/Middle/End',
                        'Check, Capture, Queen attack',
                        'Why did opponent move?',
                    ],
                    'quiz' => [
                        'Phase identification',
                        'Priority application',
                    ],
                    'assessment' => [
                        'Analyze 10-move mini game',
                    ],
                ],
                [
                    'title' => 'Opening Principles',
                    'video' => [
                        'Control center',
                        'Develop pieces',
                        'Castle early',
                        'Italian Game demo',
                    ],
                    'quiz' => [
                        'Good vs bad opening move',
                        'Principle identification',
                    ],
                    'assessment' => [
                        'Play first 5 moves of Italian correctly',
                    ],
                ],
                [
                    'title' => 'Tactical Motifs',
                    'video' => [
                        'Fork',
                        'Pin',
                        'Skewer',
                        'Discovered attack',
                    ],
                    'quiz' => [
                        'Identify tactic',
                        'Difference between pin and skewer',
                    ],
                    'assessment' => [
                        'Solve 5 tactical puzzles',
                    ],
                ],
                [
                    'title' => 'Checkmate Patterns',
                    'video' => [
                        'Back rank',
                        'Smothered mate',
                        "Scholar's mate",
                        'Arabian mate',
                    ],
                    'quiz' => [
                        'Identify mate pattern',
                        'Defensive idea',
                    ],
                    'assessment' => [
                        'Demonstrate 3 mate patterns',
                    ],
                ],
            ],
        ],
        [
            'title' => 'Module 4 - Expanding Knowledge',
            'topics' => [
                [
                    'title' => 'Opening Traps',
                    'video' => [
                        "Scholar's mate",
                        "Fool's mate",
                        "Legal's trap",
                        'Elephant trap',
                    ],
                    'quiz' => [
                        'Why trap works?',
                        'How to avoid trap?',
                    ],
                    'assessment' => [
                        'Explain 2 traps and prevention',
                    ],
                ],
                [
                    'title' => 'Advanced Tactics',
                    'video' => [
                        'Deflection',
                        'Decoy',
                        'Clearance',
                        'Underpromotion',
                    ],
                    'quiz' => [
                        'Identify advanced tactic',
                    ],
                    'assessment' => [
                        'Solve 8 mixed puzzles',
                    ],
                ],
                [
                    'title' => 'Common Checkmate Patterns',
                    'video' => [
                        'Opera mate',
                        "Boden's mate",
                        "Anastasia's mate",
                        'Ladder mate',
                    ],
                    'assessment' => [
                        'Set up and explain 3 mates',
                    ],
                ],
            ],
        ],
        [
            'title' => 'Module 5 - Advanced Chess Concepts',
            'topics' => [
                [
                    'title' => 'Deep Tactics',
                    'video' => [
                        'Zugzwang',
                        'Greek Gift',
                        'Desperado',
                        'Undermining',
                    ],
                    'assessment' => [
                        'Identify advanced motif in master game',
                    ],
                ],
                [
                    'title' => 'Pawn Endgames',
                    'video' => [
                        'Key squares',
                        'Opposition',
                        'Rook pawn rule',
                        'Rule of square',
                    ],
                    'assessment' => [
                        'Win King + Pawn vs King',
                    ],
                ],
                [
                    'title' => 'Rook Endgames',
                    'video' => [
                        'Lucena',
                        'Philidor',
                        'Rook behind pawn',
                        'Cutting king off',
                    ],
                    'assessment' => [
                        'Demonstrate Lucena',
                    ],
                ],
                [
                    'title' => 'Game Analysis with Engines',
                    'video' => [
                        'Upload game',
                        'Analyze',
                        'Mistake vs blunder',
                        'Teaching children analysis',
                    ],
                    'assessment' => [
                        'Analyze one recorded game',
                    ],
                ],
            ],
        ],
        [
            'title' => 'Module 6 - Teaching in Schools',
            'topics' => [
                ['title' => 'Subject vs Club'],
                ['title' => 'Environment setup'],
                ['title' => 'Instructional materials'],
                ['title' => 'Using Lichess/Chess.com'],
                ['title' => 'Workbooks'],
                ['title' => 'Lesson planning'],
            ],
            'every_topic_requires' => [
                'Practical classroom demonstration',
                'Lesson plan submission',
                'Peer review',
            ],
            'assessment' => [
                'Submit full lesson plan',
            ],
        ],
        [
            'title' => 'Module 7 - Adapting to Age Groups',
            'topics' => [
                ['title' => 'Ages 3-5'],
                ['title' => 'Ages 5-6'],
                ['title' => 'Ages 6-9'],
                ['title' => 'Ages 9-12'],
                ['title' => 'College students'],
                ['title' => 'Peer-led clubs'],
            ],
            'every_topic_requires' => [
                'Mini teaching demo video submission',
                'Child-friendly explanation',
                'Age-appropriate puzzle design',
            ],
        ],
        [
            'title' => 'Module 8 - Professional Development',
            'topics' => [
                ['title' => 'Instructor role'],
                ['title' => 'Tournament prep'],
                ['title' => 'Chess psychology'],
            ],
            'assessment' => [
                '2-page reflection',
                'Motivational speech',
                '4-week tournament prep plan',
            ],
        ],
    ],
    'capstone' => [
        'title' => 'Capstone - Teaching Practice',
        'workflow' => [
            'Upload 15-min teaching video',
            'Mentor reviews',
            'Feedback given',
            'Revised submission (if needed)',
            'Certification approval',
        ],
    ],
    'certification' => [
        'title' => 'Certified Genchess Instructor - Level 2',
        'requirements' => [
            'Pass all quizzes (70% minimum)',
            'Submit all assignments',
            'Complete teaching practice',
            'Receive mentor approval',
        ],
    ],
];
