<?php declare(strict_types=1);

namespace Rater\Endpoints\CreditRateLimit;

use Exception;
use Faker\Provider\Uuid;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Rater\Domain\Models\ClientRequest;
use Rater\Endpoints\JsonResponse;
use Rater\Infrastructure\Services\ClientRequestRepository;
use Rater\Services\CreditRateLimitService;
use Rater\Services\ModelMapper;

final class LimitUpdateRequestHandler
    implements RequestHandlerInterface
{
    private LoggerInterface $logger;
    private CreditRateLimitService $creditRateLimitService;
    private ClientRequestRepository $clientRequestRepository;
    private const POST = 'POST';
    public function __construct(
        LoggerInterface        $logger,
        CreditRateLimitService $service,
        ClientRequestRepository $clientRequestRepository,
    ) {
        $this->logger = $logger;
        $this->creditRateLimitService = $service;
        $this->clientRequestRepository = $clientRequestRepository;
    }

    /**
     * This project done with one week, a lot of functionality not finished yet
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // full endpoint can be with switch over http method verbs
            assert($request->getMethod() == self::POST,
                new Exception('only POST request', JsonResponse::BAD_REQUEST) );
            // some trait for share over endpoints
            assert($this->isJsonContentType($request),
                new Exception('await json', JsonResponse::BAD_REQUEST) );

            // Вхідні параметри (тип запиту post, формат даних json ):
            // clientId -- ідентифікатор клієнта (обов’язкове поле, number)
            // birthday --дата народження клієнта (обов’язкове поле, string)
            // phone -- номер телефону клієнта (не обов’язкове поле, string)

            // phone is required !!! we expect in domain logic - rate per mobile operator

            // mail – e-mail клієнта (не обов’язкове поле, string)
            // address -- адреса клієнта (не обов’язкове поле, string)
            // salary -- сума доходу клієнта (не обов’язкове поле, якщо дані відсутні то вважаємо що дохід = 0, number)
            // currency -- валюта доходу клієнта (не обов’язкове поле, якщо дані відсутні то вважаємо що валюта = UAH, string)
            // requestedLimit – бажана сума кредитного ліміту (обов’язкове поле, number)

            if (!$requestBody = $this->extractRequestBody($request))
                throw new Exception('JSON_THROW_ON_ERROR', JsonResponse::UNPROCESSABLE_CONTENT);

            // hacks for week modelMapper
            $requestBody->uuid = Uuid::uuid();
            $requestBody->decision = false;

            /** @var ClientRequest $requestModel */
            $requestModel = ModelMapper::Map($requestBody, ClientRequest::class);

            // Реалізувати валідацію вхідних даних на відповідність заданим типам даних,
            // та перевірити наявність обов’язкових полів.

            // I'm show some practice how to recursively map data,
            // and validation with attributes like idea - prototype without implementation!
            // solution for production require more time.

            if ($requestModel->hasErrors()) {
                // Якщо перевірка не пройдена,
                // повернути у відповідь помилку з рекомендацією.
                return new JsonResponse($requestModel->getErrors(), JsonResponse::UNPROCESSABLE_CONTENT);
            }

            $this->clientRequestRepository->persist(
                $this->creditRateLimitService
                    ->resolveCreditRateLimitDecision($requestModel)
            );

            // Якщо перевірка пройдена успішно,
            // сгенерувати та надати у відповідь Ref
            // (унікальний ідентифікатор заявки, string).
            return new JsonResponse([
                'ref' => $requestModel->getUuid()->getValue()
            ], JsonResponse::CREATED);

            // ta-da-mmm it is working!
            // {"http_status_code":201,"http_status_message":"Created",
            // "errors":[],"response":{"ref":"2b74450c-3c25-3be9-ab05-728d11c8087e"}}
            // approximately full time for one week
            // for data-mapper spent about two-tree days,

        } catch (Exception|\AssertionError|\TypeError $exception) {
            $this->logger->info($exception->getMessage(), [self::class]);

            // todo add fix app exceptions, codes
            return new JsonResponse($exception->getMessage(), JsonResponse::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Exception
     */
    private function extractRequestBody(ServerRequestInterface $request): object|null
    {
        $requestBody = json_decode($request->getBody()->getContents());
        if (is_object($requestBody)) return $requestBody;
        // 7.3.0	JSON_THROW_ON_ERROR flags was added.
        return null;
    }

    private function isJsonContentType(ServerRequestInterface $request): bool
    {
        // todo better check for json?
        $contentType = $request->getHeader('content-type');

        if (count($contentType)){
            if (in_array('application/json', $contentType)) return true;
        }

        return false;
    }

}
