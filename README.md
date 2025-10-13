
# Document Analyzer 
An AI-powered application that analyzes PDFs using the Retrieval-Augmented Generation (RAG) technique to deliver accurate, context-aware answers from your documents.

It simply works by uploading a PDF and prompting the LLM questions about the document. 

Built with:
- [Laravel 12](https://laravel.com/docs/12.x)
- [VueJS](https://vuejs.org/guide/introduction.html)
- [InertiaJS](https://inertiajs.com/)
- [PrismPHP](https://prismphp.com/)

## How It works (Client Side)
- User uploads a document (PDF)
- The system processes the document
- Once processing is complete, the user can prompt the LLM for queries related to the document.
- Users can start new conversations or continue from previous sessions.

## How It Works (Server Side)
- User uploads a PDF
- Text is extracted from the PDF using [Smalot PDF Parser](https://github.com/smalot/pdfparser) package.
- The extracted text is segmented into smaller chunks for efficient processing and to meet the embedding model context.
- Each chunk is sent to an embedding model to generate vector representations.
- The resulting vectors are stored in a vector database for retrieval.
- When the user submits a query, the system performs a similarity search in the vector database using the RAG (Retrieval-Augmented Generation) technique to find relevant context.
- The retrieved context is passed to the LLM, which generates a context-aware response.
- The LLM’s response is returned and displayed to the user through a chatbot interface.

## Notable Features
- `Manager Design Pattern` - The system uses a Manager pattern, allowing easy configuration and switching between different Vector Databases and LLMs.
    - Vector Database: [Qdrant](https://qdrant.tech/) 
    - LLM: [Gemini](https://gemini.google.com/app)

- `Queues` - Each text chunk is enqueued as an individual job. Batchable jobs are grouped together and processed in parallel to optimize throughput and performance.
- `Job Middleware` - To comply with the LLM’s rate limits, a middleware enforces a rate limit of 100 requests per minute (RPM) for job execution.
- `Reverb` - A real-time communication layer that enables live tracking of job progress and interactive chatbot updates. It uses event-driven architecture with WebSockets to broadcast job state changes, logs, and chatbot messages as they occur.

## Installation

- Copy this repository
- Copy the `.env.example` to `.env`
- Setup the database connection
- Install `Composer`

    ```bash
    composer install
    ```
- Generate the application key

    ```bash
    php artisan key:generate
    ```
- Migrate and seed the database 

    ```bash
    php artisan migrate:fresh --seed
    ```
- Run the project

    ```bash
    php artisan serve
    ```