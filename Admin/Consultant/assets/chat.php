<style>
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 80vh;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
        }

        .chat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #ccc;
        }

        .chat-title {
            font-size: 1.2em;
            font-weight: bold;
        }

        .chat-members {
            font-size: 0.9em;
            color: #666;
        }

        .chat-window {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background-color: #fff;
        }

        .chat-message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            background-color: #f1f1f1;
        }

        .chat-message strong {
            display: block;
            margin-bottom: 5px;
        }

        .chat-footer {
            padding: 10px;
            background-color: #f8f9fa;
            border-top: 1px solid #ccc;
            text-align: center;
        }
    </style>